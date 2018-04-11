<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', ChoiceType::class, array(
            'choices'  => array(
                'Chien' => 'Chien',
                'Chat' => 'Chat',
                'Rongeur' => 'Rongeur',
                'Oiseau' => 'Oiseau',
            ),
        ))
            ->add('age')
            ->add('sex', ChoiceType::class, array(
                'choices' => array('Male' => 'Male', 'Female' => 'Female'),
                'expanded' => true,
            ))
            ->add('couleur', TextType::class)
            ->add('messageComplementaire', TextareaType::class)
            ->add('race', TextType::class)
            ->add('typepoil', ChoiceType::class, array(
                'choices' => array(
                    'Nus' => 'Nus',
                    'Sans sous-poil' => 'Sans sous-poil',
                    'Double pelage' => 'Double pelage',
                    'Poils courts' => 'Poils courts',
                    'Poils longs' => 'Poils longs'),
            ))
            ->add('lieu_trouve', TextType::class)
            ->add('vaccin', ChoiceType::class, array(
                'choices' => array('Oui' => 'Oui', 'Non' => 'Non'),
                'expanded' => true,
            ))

            ->add('dossier', ChoiceType::class, array(
                'choices' => array('Oui' => 'Oui', 'Non' => 'Non'),
                'expanded' => true,
            ))
            ->add('save', SubmitType::class, array('label' => 'Poster'))
            ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Annonce'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_annonce';
    }


}
