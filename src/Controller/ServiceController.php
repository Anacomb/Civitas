<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\ServiceRepository;
use App\Services\Personne\PermissionChecker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @var PermissionChecker
     */
    private $permissionChecker;

    /**
     * ServiceController constructor.
     */
    public function __construct(PermissionChecker $permissionChecker)
    {
        $this->permissionChecker = $permissionChecker;
    }

    /**
     * @Route("/", name="service_index", methods={"GET"})
     */
    public function index(ServiceRepository $serviceRepository): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('fail', 'Veuillez vous connectez pour acceder à cette page.');
            return $this->redirectToRoute('app_login');
        } else {
            if (!$this->permissionChecker->isUserGranted(["GET_OTHER_SERVICE"])) {
                $this->addFlash('fail', 'Vous ne possedez pas les permissions necessaires.');
                return $this->redirectToRoute('home_index');
            } else {
                return $this->render('service/index.html.twig', [
                    'services' => $serviceRepository->findAll(),
                ]);
            }
        }
    }

    /**
     * @Route("/new", name="service_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('fail', 'Veuillez vous connectez pour acceder à cette page.');
            return $this->redirectToRoute('app_login');
        } else {
            if (!$this->permissionChecker->isUserGranted(["POST_SERVICE"])) {
                $this->addFlash('fail', 'Vous ne possedez pas les permissions necessaires.');
                return $this->redirectToRoute('home_index');
            } else {
                $service = new Service();
                $form = $this->createForm(ServiceType::class, $service);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($service);
                    $entityManager->flush();
                    return $this->redirectToRoute('service_index');

                }
                return $this->render('service/new.html.twig', [
                    'service' => $service,
                    'form' => $form->createView(),
                ]);
            }
        }
    }

    /**
     * @Route("/{id}/edit", name="service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Service $service): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('fail', 'Veuillez vous connectez pour acceder à cette page.');
            return $this->redirectToRoute('app_login');
        } else {
            if (!$this->permissionChecker->isUserGranted(["UPDATE_OTHER_SERVICE"])) {
                $this->addFlash('fail', 'Vous ne possedez pas les permissions necessaires.');
                return $this->redirectToRoute('home_index');
            } else {
                $form = $this->createForm(ServiceType::class, $service);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $this->getDoctrine()->getManager()->flush();

                    return $this->redirectToRoute('service_index');
                }

                return $this->render('service/edit.html.twig', [
                    'service' => $service,
                    'form' => $form->createView(),
                ]);
            }
        }
    }

    /**
     * @Route("/{id}", name="service_show", methods={"GET"})
     */
    public function show(Service $service): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('fail', 'Veuillez vous connectez pour acceder à cette page.');
            return $this->redirectToRoute('app_login');
        } else {
            if (!$this->permissionChecker->isUserGranted(["GET_OTHER_SERVICE"])) {
                $this->addFlash('fail', 'Vous ne possedez pas les permissions necessaires.');
                return $this->redirectToRoute('home_index');
            } else {
                return $this->render('service/show.html.twig', [
                    'service' => $service,
                ]);
            }
        }
    }

    /**
     * @Route("/{id}", name="service_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Service $service): Response
    {
        if (!$this->isGranted('ROLE_USER')) {
            $this->addFlash('fail', 'Veuillez vous connectez pour acceder à cette page.');
            return $this->redirectToRoute('app_login');
        } else {
            if (!$this->permissionChecker->isUserGranted(["DELETE_OTHER_SERVICE"])) {
                $this->addFlash('fail', 'Vous ne possedez pas les permissions necessaires.');
                return $this->redirectToRoute('home_index');
            } else {
                if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->request->get('_token'))) {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($service);
                    $entityManager->flush();
                }
            }
        }
        return $this->redirectToRoute('service_index');
    }
}
