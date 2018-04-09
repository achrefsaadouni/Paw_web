<?php
/**
 * Created by PhpStorm.
 * User: achref
 * Date: 09/04/2018
 * Time: 00:09
 */

namespace AppBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')->add('prenom')->add('addresse') ->add('numero') ->add('sexe', ChoiceType::class, array(
            'choices' => array('Homme' => 'homme', 'Femme' => 'femme'),
            'expanded' => true,
        ));
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getName()
    {
        return 'app_user_profile';
    }
}