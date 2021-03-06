<?php


namespace App\Form;


use App\Entity\Personne;
use App\Entity\Role;
use App\Repository\HistoriqueStatutRepository;
use App\Repository\PersonneRepository;
use App\Repository\ProblemeRepository;
use App\Repository\RoleRepository;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddRoleToSomeoneType extends AbstractType
{

        public function buildForm(FormBuilderInterface $builder, array $options)
        {
        $builder
            ->add('role', EntityType::class,[
                'class' => Role::class,
                'query_builder' => function (RoleRepository $er) {
                    return $er->createQueryBuilder('r');},
                'choice_label' => 'Label'
            ])
        ;
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            [$this, 'onPreSetData']
        );

    }

    public function onPreSetData(FormEvent $event)
    {
        $form = $event->getForm();
        $entity = $event->getData();
        if ($entity != null) {
            $form->add('personne', EntityType::class,[
                'class'=> Personne::class,
                'data' => $entity,

            ]);
        }else{
            $form->add('personne', EntityType::class,[
                'class' => Personne::class,
                'query_builder' => function (PersonneRepository $er) {
                    return $er->createQueryBuilder('p');},
                'choice_label' => 'Label'
            ]);
        }
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}