<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Annonce;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class GardeController extends Controller
{
    /**
     * @Route("/AjouterAnnonceGarde",name="ajouter_annonce_garde")
     */
    public function AjouterAnnonceGardeAction(Request $request)
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

            $annonce->setDatesit(new \DateTime($request->get('datesit')));
            $annonce->setDuresit($request->get('duresit'));
            $annonce->setTodolist($request->get('todolist'));

            $annonce->setMessageComplementaire($request->get('message'));


            $annonce->setUtilisateur($this->get('security.token_storage')->getToken()->getUser());

            $annonce->setTypeAnnonce("Annonce Sitting");

            $file = $request->files->get('image');
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('annonce_directory'),
                $fileName
            );
            $annonce->setImages($fileName);

            $em->persist($annonce);
            $em->flush();

            return $this->redirectToRoute('lister_annonce_garde');
        }


        return $this->render('AppBundle:Services:ajouter_annonce_garde.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/listerMesAnnoncesGarde", name="lister_annonce_garde")
     */
    public function ListerAnnonceGardeAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $annonce = $em->getRepository("AppBundle:Annonce")
            ->findBy(
                [
                    'utilisateur'=>$this->get('security.token_storage')->getToken()->getUser(),
                    'typeAnnonce'=>'Annonce Sitting'
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

        return $this->render('AppBundle:Services:lister_annonce_garde.html.twig', array(
            'm' => $annonce,
            'user' => $user
        ));
    }


    /**
     * @Route("/AfficherAnnonceGarde/{id}", name="afficher_annonce_garde")
     */
    public function AfficherAnnonceGardeAction($id)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->find($id);

        return $this->render('AppBundle:Services:afficher_annonce_garde.html.twig',array(

            'annonce'=>$annonce,
            'user'=>$user
        ));

    }

    /**
     * @Route("/modifierAnnonceGarde/{id}", name="modifier_annonce_garde")
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
            $annonce->setDatesit(new \DateTime($request->get('datesit'))) ;
            $annonce->setDuresit($request->get('duresit'));
            $annonce->setTodolist($request->get('todolist'));
            $annonce->setMessageComplementaire($request->get('message'));
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute("lister_annonce_garde", array('id'=>$id));

        }


        return $this->render('AppBundle:Services:modifier_annonce_garde.html.twig',
            ['m'=>$annonce , 'user'=>$user]
        );
    }

    /**
     * @Route("/SupprimerAnnonceGarde/{id}", name="supprimer_annonce_garde")
     */
    public function supprimerAction( $id)
    {

        $em = $this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);


        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute("lister_annonce_garde", array('id'=>$id));

    }

    /**
     * @Route("/AjouterAnnonceGarde2",name="ajouter_annonce_garde2")
     */
    public function AjouterAnnonceGarde2Action(Request $request)
    {

        $em = $this->getDoctrine()->getManager();

        $annonce = new Annonce();
        $annonce->setAge($request->get('age'));
        $annonce->setType($request->get('type'));
        $annonce->setSex($request->get('sexe'));
        $annonce->setRace($request->get('race'));
        $annonce->setCouleur($request->get('couleur'));
        $utilisateur= $em->getRepository("AppBundle:Utilisateur")->find($request->get("user"));
        $annonce->setUtilisateur($utilisateur);
        // $annonce->setDatesit(new \DateTime($request->get('date')));

        $annonce->setDuresit($request->get('dureSit'));
        $annonce->setTodolist($request->get('todolist'));

        // $annonce->setDate($date->format("Y-m-d"));

        $annonce->setMessageComplementaire($request->get('message'));
        $annonce->setTypeAnnonce("Annonce Sitting");

        //     exit(VarDumper::dump($annonce));
        $em->persist($annonce);
        $em->flush();
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data =$serializer->normalize($annonce) ;
        return new JsonResponse($data) ;
    }

    /**
     * @Route("/getListGarde" ,name="/get_List_Garde")
     */
    public function GetAllAction()
    {
        $em=$this->getDoctrine()->getManager()->getRepository("AppBundle:Annonce")->findBy(['typeAnnonce'=>'Annonce Sitting']);
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize($em);
        return new JsonResponse($data) ;


    }


    /**
     * @Route("/AfficherAnnonceGarde2/{id}", name="afficher_annonce_Garde2")
     */
    public function AfficherAnnonceGardeAction2($id)
    {

        $em=$this->getDoctrine()->getManager()->getRepository("AppBundle:Annonce")->findBy(['id'=>$id]);
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize($em);
        return new JsonResponse($data) ;

    }

}