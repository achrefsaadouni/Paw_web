<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProduitController extends Controller
{
    /**
     * @Route("/boutique" ,name="boutique_membre")
     */
    public function boutiqueAction(Request $request) {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:boutique.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/admin/gererproduit" ,name="gererproduit_admin")
     */
    public function gererProduitAction(Request $request) {
        $user = $this->getUser();
        return $this->render('AppBundle:admin:produit.html.twig',array(
            'user' => $user,
        ));
    }
}
