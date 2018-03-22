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

        return $this->render('AppBundle:Membre:index.html.twig',array(

        ));
    }
    /**
     * @Route("/admin/index", name="home_admin")
     */
    public function indexAdminAction(Request $r)
    {

        return $this->render('AppBundle:Admin:index.html.twig',array(

        ));
    }


    /**
     * @Route("/annonce_trouve", name="annonce_trouveName")
     */
    public function annonceTrouveAction()
    {

        return $this->render('AppBundle:Membre:annonce_trouve.html.twig',array(

        ));
    }

    /**
     * @Route("/veterinaire", name="veterinaireName")
    /**
     * @Route("/boutique", name="boutique_ProduitsName")
     */
    public function boutiqueProduitsAction()
    {

        return $this->render('AppBundle:Membre:boutique_produits.html.twig',array(

        ));
    }
    /**
     * @Route("/reclamation", name="reclamationName")
     */
    public function reclamationAction()
    {

        return $this->render('AppBundle:Membre:reclamation.html.twig',array(

        ));
    }
    /**
     * @Route("/adoption", name="adoptionName")
     */
    public function adoptionPrincipaleAction()
    {

        return $this->render('AppBundle:Membre:adoption_liste.html.twig',array(

        ));
    }
    /**
     * @Route("/veterinaires", name="veterinaireName")
     */
    public function veterinaireAction()
    {

        return $this->render('AppBundle:Membre:veterinaire.html.twig',array(

        ));
    }
    /**
     * @Route("/accouplement", name="accouplementName")
     */
    public function accouplementAction()
    {

        return $this->render('AppBundle:Membre:accouplement_liste.html.twig',array(

        ));
    }
    /**
     * @Route("/sitting", name="sittingName")
     */
    public function sittingAction()
    {

        return $this->render('AppBundle:Membre:sitting.html.twig',array(

        ));
    }
    /**
     * @Route("/walking", name="walkingName")
     */
    public function walkingAction()
    {

        return $this->render('AppBundle:Membre:walking.html.twig',array(

        ));
    }
}
