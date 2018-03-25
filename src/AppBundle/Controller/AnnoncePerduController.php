<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AnnoncePerduController extends Controller
{
    /**
     *
     * @Route("/annonce_perdu", name="annonce_perduName")
     */
    public function annoncePerduAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:annonce_perdu.html.twig',array(
            'user' => $user,
        ));
    }
}
