<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reclamation;
use AppBundle\Entity\Reponsereclamation;
use AppBundle\Form\ReponsereclamationType;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\ReclamationType;

class ReclamationController extends Controller
{
    /**
     * @Route("/reclamation" ,name="reclamation_membre")
     */
    public function AfficheAction(Request $request) {
        $user = $this->getUser();
        return $this->render('AppBundle:Ayoub:reclamation.html.twig',array(
            'user' => $user,
        ));
    }
    /**
     * @Route("/reclamationAjout" ,name="reclamationAjout_membre")
     */
    public function AjoutAction(Request $request)
    {
        $user = $this->getUser();
        $rec=new Reclamation();
        $em=$this->getDoctrine()->getManager();
        $Form=$this->createForm(ReclamationType::class,$rec);
        $Form->handleRequest($request);  // handlerequest use POST in default
        if($Form->isSubmitted()){
            $rec->setIdUtilisateur($user);
            $rec->setDate(new \DateTime('now', (new \DateTimeZone('Africa/Tunis'))));
            $rec->setEtat('Non traitée');

            $em->persist($rec);
            $em->flush();
            return $this->redirectToRoute('MesReclamations_membre');
        }
        return $this->render('AppBundle:Ayoub:ajoutReclamation.html.twig',
            array('form'=>$Form->createView()
            ,'user' => $user
            ));
    }

    /**
     * @Route("/MesReclamations" ,name="MesReclamations_membre")
     */
    public function listAction(Request $request)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT a
        FROM AppBundle:Reclamation a
        WHERE a.idUtilisateur = :iduser'
        )->setParameter('iduser', $this->getUser());

        $results=$query->getResult();
        // parameters to template
        return $this->render('AppBundle:Ayoub:afficheMyReclamations.html.twig', array('liste' => $results,'user' => $user));
    }



    /**
     * @Route("/ReponseReclamation/{id}" ,name="voirRéponse_membre")
     */
    public function ReponseAction($id){
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $rep = $em->getRepository('AppBundle:Reponsereclamation')->findOneBy(array('idReclamation' => $id));
        $rec=$em->getRepository('AppBundle:Reclamation')->find($id);

        return $this->render('AppBundle:Ayoub:ReponseReclamation.html.twig',
            array('user' => $user
            ,'reponse' => $rep
            ,'reclamation' => $rec
            ));
    }

    /*
     * **************************************************************************
     * *******************************Actions Admin******************************
     * **************************************************************************
     */


    /**
     * @Route("/admin/LesReclamationsNonTraitees" ,name="LesReclamationsNonTraitees_Admin")
     */
    public function AfficheAdminAction(Request $request)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT a
        FROM AppBundle:Reclamation a
        WHERE a.etat = :t'
        )->setParameter('t', 'Non Traitée');
        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $results=$paginator-> paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',100)
        );
        return $this->render('AppBundle:AyoubAdmin:ListeReclamationsNonTraitees.html.twig', array('pagination' => $results,'user' => $user));
    }

    /**
     * @Route("/admin/LesReclamationsTraitees" ,name="LesReclamationsTraitees_Admin")
     */
    public function Affiche2AdminAction(Request $request)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT a
        FROM AppBundle:Reclamation a
        WHERE a.etat = :t'
        )->setParameter('t', 'Traitée');
        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $results=$paginator-> paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',100)
        );
        return $this->render('AppBundle:AyoubAdmin:ListeReclamationsTraitees.html.twig', array('pagination' => $results,'user' => $user));
    }

    /**
     * @Route("/admin/RépondreReclamation/{id}" ,name="repondreReclamation_admin")
     */
    public function RepondreAction(Request $request,$id){
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $rec=$em->getRepository('AppBundle:Reclamation')->find($id);

        $rep=new Reponsereclamation();

        $Form=$this->createForm(ReponsereclamationType::class,$rep);
        $Form->handleRequest($request);  // handlerequest use POST in default
        if($Form->isSubmitted()){
            $rec->setEtat('Traitée');

            $rep->setIdReclamation($rec);
            $rep->setDate(new \DateTime('now', (new \DateTimeZone('Africa/Tunis'))));
            $em->persist($rep);
            $em->flush();
            return $this->redirectToRoute('LesReclamationsNonTraitees_Admin');
        }
        return $this->render('AppBundle:AyoubAdmin:RepondreReclamation.html.twig',
            array('form'=>$Form->createView()
            ,'user' => $user
            ,'reclamation' => $rec
            ));
    }


    /**
     * @Route("/admin/statFeed", name="statFeedback_admin")
     */
    public  function stataction()
    {
        $pieChart=new PieChart();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $dispo = $em->getRepository('AppBundle:Reclamation')->findBy(array('etat'=>'Traitée'));
        $ndispo = $em->getRepository('AppBundle:Reclamation')->findBy(array('etat'=>'Non traitée'));
        $nbr1 = 0;
        $nbr2 = 0 ;
        foreach ($dispo as $d)
        {
            $nbr1 = $nbr1 + 1 ;
        }
        foreach ($ndispo as $n)
        {
            $nbr2 = $nbr2+1;
        }

        $pieChart->getData()->setArrayToDataTable(
            [['Feedback traitées', 'Feedback non traitées'],
                ['Feedback traitées',     $nbr1],
                ['Feedback non traitées',      $nbr2],

            ]
        );

        $pieChart->getOptions()->setTitle('Les statistiques des feedback');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);




        return $this->render('AppBundle:AyoubAdmin:statFeed.html.twig', array(
            'piechart'=>$pieChart ,
            'user'=>$user
        ));
    }

}
