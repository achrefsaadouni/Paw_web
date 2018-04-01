<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('libelle')->add('prix')->add('quantite')->add('description')->add('type',
            ChoiceType::class ,array('choices'=>array('Laisse, Collier et Harnais'=>'Laisse, Collier et Harnais',
                'Lits et Couvertures'=>'Lits et Couvertures',
                'Shampoings et Conditionneurs'=>'Shampoings et Conditionneurs',
                'Vetements'=>'Vetements',
                'Bols'=>'Bols',
                'Cadeaux'=>'Cadeaux',
                'Gâteries Et Nourritures'=>'Gâteries Et Nourritures',
                'Jouets' =>'Jouets'

            ))


            )->add('image1',FileType::class)->add('image2',FileType::class);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_produit';
    }


}
