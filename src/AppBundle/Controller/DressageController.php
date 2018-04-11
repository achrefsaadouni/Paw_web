<?php


namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DressageController extends Controller
{
    /**
     * @Route("/AjouterAnnonceDressage",name="ajouter_annonce_dressage")
     */
    public function AjouterAnnonceDressageAction(Request $request)
    {
        $user = $this->getUser();
        if ($request->isMethod('post')) {

            $em = $this->getDoctrine()->getManager();

            $annonce = new Annonce();
            $annonce->setAge($request->get('age'));
            $annonce->setType($request->get('type'));
            $annonce->setSex($request->get('sexe'));
            $annonce->setRace($request->get('race'));

            $annonce->setCouleur($request->get('couleur'));

            $annonce->setDatetr(new \DateTime($request->get('date')));
            $annonce->setTypetr($request->get('typeTr'));

            $annonce->setMessageComplementaire($request->get('message'));

            // $annonce->setDate($date->format("Y-m-d"));

            $annonce->setUtilisateur($this->get('security.token_storage')->getToken()->getUser());

            $annonce->setTypeAnnonce("Annonce Training");


            $file = $request->files->get('image');
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('annonce_directory'),
                $fileName
            );

            $annonce->setImages($fileName);
//            exit(VarDumper::dump($annonce));
            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('lister_annonce_dressage');
        }


        return $this->render('AppBundle:Services:ajouter_annonce_dressage.html.twig', array(
            'user' => $user
        ));
    }


    /**
     * @Route("/AfficherAnnonceDressage/{id}", name="afficher_annonce_dressage")
     */
    public function AfficherAnnonceDresageAction($id)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->find($id);

        return $this->render('AppBundle:Services:afficher_annonce_dressage.html.twig',array(

            'annonce'=>$annonce,
            'user'=>$user
        ));

    }



    /**
     * @Route("/listerMesAnnoncesDressage", name="lister_annonce_dressage")
     */
    public function ListerAnnonceDressageAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $annonce = $em->getRepository("AppBundle:Annonce")
            ->findBy(
                [
                    'utilisateur'=>$this->get('security.token_storage')->getToken()->getUser(),
                    'typeAnnonce'=>'Annonce Training'
                ]


            );

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator = $this->get('knp_paginator');
        $annonce = $paginator->paginate(
            $annonce,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)

        );

        return $this->render('AppBundle:Services:lister_annonce_dressage.html.twig', array(
            'm' => $annonce,
            'user' => $user
        ));
    }


    /**
     * @Route("/modifierAnnonceDressage/{id}", name="modifier_annonce_dressage")
     */
    public function modifierAction(Request $request, $id)
    {
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);
        $dir=$_SERVER['DOCUMENT_ROOT'];

        if($request->isMethod('post')){

            $annonce->setAge($request->get('age'));
            $annonce->setType($request->get('type'));
            $annonce->setSex($request->get('sexe'));
            $annonce->setRace($request->get('race'));
            $annonce->setCouleur($request->get('couleur'));
            $annonce->setDatetr(new \DateTime($request->get('dateTr'))) ;
            $annonce->setTypetr($request->get('typeTr'));
            $annonce->setMessageComplementaire($request->get('message'));
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute("lister_annonce_dressage", array('id'=>$id));

        }


        return $this->render('AppBundle:Services:modifier_annonce_dressage.html.twig',
            ['m'=>$annonce , 'user'=>$user]
        );
    }

    /**
     * @Route("/SupprimerAnnonceDressage/{id}", name="supprimer_annonce_dressage")
     */
    public function supprimerAction( $id)
    {

        $em = $this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);


        $image = $annonce->getImages();
        unlink($this->getParameter('annonce_directory') . '/' . $image);

        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute("lister_annonce_dressage", array('id'=>$id));

    }
}