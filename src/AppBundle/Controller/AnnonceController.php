<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Annonce;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\VarDumper\VarDumper;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AnnonceController extends Controller
{
    /**
     * @Route("/AjouterAnnonceTrouver",name="ajouter_annonce_trouver")
     */
    public function AjouterAnnonceTrouverAction(Request $request)
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
            $annonce->setColier($request->get('colier'));
            $annonce->setDateTrouvee(new \DateTime($request->get('date'))) ;

            $annonce->setLieuTrouve($request->get('lieu'));
            $annonce->setMessageComplementaire($request->get('message'));

            // $annonce->setDate($date->format("Y-m-d"));

            $annonce->setUtilisateur($this->get('security.token_storage')->getToken()->getUser());

            $annonce->setTypeAnnonce("annonce trouve");



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

            return $this->redirectToRoute('lister_annonce_trouver');
        }


            return $this->render('AppBundle:Annonce:ajouter_annonce_trouver.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/listerAnnonceTrouver/{id}", name="afficher_annonce_trouver")
     */
    public function AfficherAnnonceTrouverAction($id)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->find($id);

        return $this->render('AppBundle:Annonce:afficher_annonce_trouver.html.twig',array(

            'annonce'=>$annonce,
            'user'=>$user
        ));

    }



    /**
     * @Route("/listerAnnonceTrouver", name="lister_annonce_trouver")
     */
    public function listerAnnonceTrouverAction(Request $request)
    {


        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy(['typeAnnonce'=>'annonce trouve']);


        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',8)

        );

        return $this->render('AppBundle:Annonce:lister_annonce_trouver.html.twig', array(
            'm'=>$annonce,
            'user'=> $user
        ));
    }






    /**
     * @Route("/listerMesAnnonce", name="afficher_mes_annonce")
     */
    public function AfficherMesAnnonceTrouverAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $annoncetrouve = $em->getRepository("AppBundle:Annonce")
            ->findBy(
                [
                    'utilisateur'=>$this->get('security.token_storage')->getToken()->getUser(),
                    'typeAnnonce'=>'annonce trouve'
                ]


            );


        $annonceperdu = $em->getRepository("AppBundle:Annonce")
            ->findBy(
                [
                    'utilisateur'=>$this->get('security.token_storage')->getToken()->getUser(),
                    'typeAnnonce'=>'annonce perdu'
                ]


            );

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator = $this->get('knp_paginator');
        $annoncetrouve = $paginator->paginate(
            $annoncetrouve,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 8)

        );
        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */
        $annonceperdu = $paginator->paginate(
            $annonceperdu,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 4)

        );

        return $this->render('AppBundle:Annonce:afficher_mes_annonce.html.twig', array(
            'm' => $annoncetrouve,
            'k' => $annonceperdu,
            'user' => $user
        ));
    }




    /**
     * @Route("/modifierAnnonceTrouver/{id}", name="modifier_annonce_trouver")
     */
    public function modifierAction(Request $request, $id)
    {
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);
        $dir=$_SERVER['DOCUMENT_ROOT'];




        if($request->isMethod('post')){

            $image = $annonce->getImages();
            unlink($dir."/paw_web/web/images/pawLostFound/".$image);
            $file = $request->files->get('image');
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('annonce_directory'),
                $fileName
            );

            $annonce->setImages($fileName);


            $annonce->setAge($request->get('age'));
            $annonce->setType($request->get('type'));
            $annonce->setSex($request->get('sexe'));
            $annonce->setRace($request->get('race'));
            $annonce->setCouleur($request->get('couleur'));
            $annonce->setColier($request->get('colier'));

            $annonce->setDateTrouvee(new \DateTime($request->get('date'))) ;
            $annonce->setLieuTrouve($request->get('lieu'));
            $annonce->setMessageComplementaire($request->get('message'));
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute("afficher_mes_annonce", array('id'=>$id));

        }


        return $this->render('AppBundle:Annonce:modifier_annonce_trouver.html.twig',
            ['m'=>$annonce , 'user'=>$user]
        );

    }


    /**
     * @Route("/supprimerAnnonceTrouver/{id}", name="supprimer_annonce_trouver")
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
        return $this->redirectToRoute("afficher_mes_annonce", array('id'=>$id));

    }



    /////////////////////////////////////////////////////////////////////////////////////////////



    /**
     * @Route("/AjouterAnnoncePerdue",name="ajouter_annonce_perdue")
     */
    public function AjouterAnnoncePerdueAction(Request $request)
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
            $annonce->setColier($request->get('colier'));
            $annonce->setDatePerte(new \DateTime($request->get('date'))) ;

            $annonce->setLieuPerdu($request->get('lieu'));
            $annonce->setMessageComplementaire($request->get('message'));

            // $annonce->setDate($date->format("Y-m-d"));

            $annonce->setUtilisateur($this->get('security.token_storage')->getToken()->getUser());

            $annonce->setTypeAnnonce("annonce perdu");



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

       //     return $this->redirectToRoute('lister_annonce_trouver');
        }



        return $this->render('AppBundle:Annonce:ajouter_annonce_perdue.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/ListerAnnoncePerdue" , name="lister_annonce_perdue")
     */
    public function ListerAnnoncePerdueAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy(['typeAnnonce'=>'annonce perdu']);


        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_perdue.html.twig', array(
            'm'=>$annonce,
            'user'=> $user
        ));
    }



    /**
     * @Route("/listerAnnoncePerdu/{id}", name="afficher_annonce_perdu")
     */
    public function AfficherAnnoncePerduAction($id)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->find($id);

        return $this->render('AppBundle:Annonce:afficher_annonce_perdu.html.twig',array(

            'annonce'=>$annonce,
            'user' => $user
        ));

    }





    /**
     * @Route("/modifierAnnoncePerdu/{id}", name="modifier_annonce_perdu")
     */
    public function modifier2Action(Request $request, $id)
    {

        $em=$this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);
        $user = $this->getUser();




        if($request->isMethod('post')){

            $image = $annonce->getImages();
            unlink($this->getParameter('annonce_directory').'/'.$image);


            $file = $request->files->get('image');
            $fileName = md5(uniqid()) . '.' . $file->guessExtension();
            $file->move(
                $this->getParameter('annonce_directory'),
                $fileName
            );

            $annonce->setImages($fileName);


            $annonce->setAge($request->get('age'));
            $annonce->setType($request->get('type'));
            $annonce->setSex($request->get('sexe'));
            $annonce->setRace($request->get('race'));
            $annonce->setCouleur($request->get('couleur'));
            $annonce->setColier($request->get('colier'));

            $annonce->setDatePerte(new \DateTime($request->get('date'))) ;
            $annonce->setLieuPerdu($request->get('lieu'));
            $annonce->setMessageComplementaire($request->get('message'));
            $em->persist($annonce);
            $em->flush();
            return $this->redirectToRoute("afficher_mes_annonce", array('id'=>$id));

        }


        return $this->render('AppBundle:Annonce:modifier_annonce_perdu.html.twig',
            ['m'=>$annonce,'user' => $user]
        );

    }


    /**
     * @Route("/supprimerAnnoncePerdu/{id}", name="supprimer_annonce_perdu")
     */
    public function supprimer2Action( $id)
    {

        $em = $this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);


        $image = $annonce->getImages();
        unlink($this->getParameter('annonce_directory') . '/' . $image);

        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute("afficher_mes_annonce", array('id'=>$id));

    }







    /**
     * @Route("/ListerAnnoncePerdueAdmin" , name="annonceperdu")
     */
    public function ListerAnnoncePerdueAdminAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy(['typeAnnonce'=>'annonce perdu']);


        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Admin:annonceperdu.html.twig', array(
            'm'=>$annonce,
            'user'=> $user
        ));
    }



    /**
     * @Route("/supprimerAnnoncePerdu/{id}", name="supprimer_annonce_perdu")
     */
    public function supprimer3Action( $id)
    {

        $em = $this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);


        $image = $annonce->getImages();
        unlink($this->getParameter('annonce_directory') . '/' . $image);

        $em->remove($annonce);
        $em->flush();
        return $this->redirectToRoute("annonceperdu", array('id'=>$id));

    }






}
