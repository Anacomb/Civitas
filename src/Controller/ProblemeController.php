<?php

namespace App\Controller;

use App\Entity\HistoriqueStatut;
use App\Entity\Image;
use App\Entity\Intervenir;
use App\Entity\Personne;
use App\Entity\Probleme;
use App\Form\ProblemeType;
use App\Form\RedirectProblemeType;
use App\Repository\CategorieRepository;
use App\Repository\CommuneRepository;
use App\Repository\ImageRepository;
use App\Repository\PrioriteRepository;
use App\Repository\ProblemeRepository;
use App\Repository\StatutRepository;
use App\Services\Geocoder\GeocoderService;
use App\Services\Mailer\MailerService;
use App\Services\Probleme\ProblemeService;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @Route("/probleme")
 */
class ProblemeController extends AbstractController
{

    private $personne;

    public function __construct(
        TokenStorageInterface $tokenStorageInterface
    ){
        $this->personne = $tokenStorageInterface->getToken()->getUser();
    }
    /**
     * @Route("/", name="probleme_index", methods={"GET"})
     */
    public function index(ProblemeRepository $problemeRepository): Response
    {
        return $this->render('probleme/index.html.twig', [
            'problemes' => $problemeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="probleme_new", methods={"GET","POST"})
     */
    public function new(
        Request $request,
        GeocoderService $geocoderService,
        SessionInterface $session,ProblemeService $problemeService
    ): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $probleme = new Probleme();

        $form = $this->createForm(ProblemeType::class, $probleme);
        $form->handleRequest($request);
        $imageArray = []; // 1,2,3,4

        $lng = $request->query->get('lng');
        $lat = $request->query->get('lat');

        $lng && $lat ? $adresse = $geocoderService->getAdressFromCoordinate($lat, $lng) : $adresse = null;

        if ($form->isSubmitted() && $form->isValid()) {
            if($this->personne != "anon.") {
                $problemeService->CreateNewProblemeAuthentificated($probleme,$this->personne);
            }else{
                $session->set('titre',$probleme->getTitre());
                $session->set('description',$probleme->getDescription());
                $session->set('localisation',$probleme->getLocalisation());
                $session->set('commune',$probleme->getCommune());
                $session->set('categorie',$probleme->getCategorie());
                $session->set('priorite',$probleme->getPriorite());
                return $this->redirectToRoute('probleme_redirect',[
                    'titre' => $probleme->getTitre(),
                ]);
            }

            for ($i = 1; $i <= 4; $i++) {
                $imageArray[$i] = new Image();
                $imageToProbleme = $form['Image' . $i]->getData();
                if ($imageToProbleme) {
                    $originalFilename = pathinfo($imageToProbleme->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = transliterator_transliterate(
                        'Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()',
                        $originalFilename);
                    $newFilename = $safeFilename . '-' . uniqid() . '.' .
                        $imageToProbleme->guessExtension();
                    // Move the file to the directory where brochures are stored
                    try {
                        $imageToProbleme->move(
                            $this->getParameter('probleme_directory'),
                            $newFilename
                        );
                    } catch (FileException $e) {
                        $this->addFlash('danger',
                            'Error on fileUpload :' . $e->getMessage());
                        return $this->redirectToRoute('home');
                    }

                    if ($imageArray[$i] != null) {
                        $imageArray[$i]->setProbleme($probleme);
                        $imageArray[$i]->setURL($newFilename);
                        $entityManager->persist($imageArray[$i]);
                    }
                }
            }



            return $this->redirectToRoute('probleme_index');
        }
        return $this->render('probleme/new.html.twig', [
            'probleme' => $probleme,
            'form' => $form->createView(),
            'adresse' => $adresse 
        ]);
    }

    /**
     * @Route("/{id}", name="probleme_show", methods={"GET"})
     */
    public function show(Probleme $probleme,ImageRepository $imageRepository): Response
    {

        return $this->render('probleme/show.html.twig', [
            'probleme' => $probleme,
            'images' => $imageRepository->findbyProbleme($probleme)

        ]);
    }

    /**
     * @Route("/{id}/edit", name="probleme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Probleme $probleme): Response
    {
        $form = $this->createForm(ProblemeType::class, $probleme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('probleme_index');
        }

        return $this->render('probleme/edit.html.twig', [
            'probleme' => $probleme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="probleme_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Probleme $probleme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$probleme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($probleme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('probleme_index');
    }

    /**
     * @Route("/redirect/{titre}", name="probleme_redirect", methods={"GET","POST"})
     */
    public function redirectUserWhenAnonyme(ProblemeService $problemeService,Request $request,  SessionInterface $session,CommuneRepository $communeRepository,CategorieRepository $categorieRepository, PrioriteRepository $prioriteRepository): Response
    {


        $form = $this->createForm(RedirectProblemeType::class);
        $form->handleRequest($request);
        $probleme = new Probleme();

        $probleme->setTitre($session->get('titre'));
        $probleme->setDescription($session->get('description'));
        $probleme->setLocalisation($session->get('localisation'));

        $commune = $communeRepository->findOneBy(['id'=> $session->get('commune')->getId()]);
        $categorie =$categorieRepository->findOneBy(['id' =>$session->get('categorie')->getId()]);
        $priorite = $prioriteRepository->findOneBy(['id' => $session->get('priorite')->getId()]);

        $probleme->setCommune($commune);
        $probleme->setCategorie($categorie);
        $probleme->setPriorite($priorite);
        $probleme->setDateDeDeclaration(new \DateTime('now'));
        $probleme->setReference(456654456);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($probleme);
            $problemeService->CreateNewIntervenirNonAuthentificated($probleme,$request->request->all()["redirect_probleme"]["mail"]);
            $entityManager->flush();
            return $this->redirectToRoute('probleme_index');
        }
        return $this->render('probleme/redirect.html.twig',[
            'form' => $form->createView()
        ]);

    }
}
