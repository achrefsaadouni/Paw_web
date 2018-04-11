<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReclamationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('objet', ChoiceType::class, array(
            'choices'  => array(
                'Boutique' => 'Boutique',
                'Service Lost&Found' => 'Service Lost&Found',
                'Service Adoption' => 'Service Adoption',
                'Service Accouplement' => 'Service Accouplement',
                'Service Walking' => 'Service Walking',
                'Service Dressage' => 'Service Dressage',
                'Boutique' => 'Boutique',
                'Fausse annonce' => 'Fausse annonce',
                'Membre' => 'Membre',
                ),
        ))->add('text')
            ->add('type', ChoiceType::class, array(
            'choices' => array('Remerciment' => 'Remerciment', 'Reclamation' => 'Reclamation'),
            'expanded' => true,
        ))->add('Ajouter',SubmitType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Reclamation'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_reclamation';
    }


}
