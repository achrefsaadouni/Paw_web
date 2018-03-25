<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/index", name="home_member")
     */
    public function indexAction(Request $r)
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:index.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/admin/index", name="home_admin")
     */
    public function indexAdminAction(Request $r)
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Admin:index.html.twig',array(
            'user' => $user,
        ));
    }


    /**
     * @Route("/annonce_trouve", name="annonce_trouveName")
     */
    public function annonceTrouveAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:annonce_trouve.html.twig',array(
            'user' => $user,
        ));
    }

    /**
     * @Route("/veterinaire", name="veterinaireName")
    /**
     * @Route("/boutique", name="boutique_ProduitsName")
     */
    public function boutiqueProduitsAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:boutique_produits.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/reclamation", name="reclamationName")
     */
    public function reclamationAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:reclamation.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/adoption", name="adoptionName")
     */
    public function adoptionPrincipaleAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:adoption_liste.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/veterinaires", name="veterinaireName")
     */
    public function veterinaireAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:veterinaire.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/accouplement", name="accouplementName")
     */
    public function accouplementAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:accouplement_liste.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/sitting", name="sittingName")
     */
    public function sittingAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:sitting.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/walking", name="walkingName")
     */
    public function walkingAction()
    {
        $user = $this->getUser();
        return $this->render('AppBundle:Membre:walking.html.twig',array(
            'user' => $user,
        ));
    }
}
