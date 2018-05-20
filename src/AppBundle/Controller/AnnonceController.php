<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Annonce;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
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

            $annonce->setTypeAnnonce("annonce_trouve");



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
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy(['typeAnnonce'=>'annonce_trouve']);


        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );

        return $this->render('AppBundle:Annonce:lister_annonce_trouver.html.twig', array(
            'm'=>$annonce,
            'user'=> $user
        ));
    }






    /**
     * @Route("/client/listerTousMesAnnonce", name="show_annonce")
     */
    public function AfficherMesAnnonceTrouverAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $annoncetrouve = $em->getRepository("AppBundle:Annonce")
            ->findBy(
                [
                    'utilisateur'=>$this->get('security.token_storage')->getToken()->getUser(),
                    'typeAnnonce'=>'annonce_trouve'
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
            $request->query->getInt('limit', 4)

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

            return $this->redirectToRoute('lister_annonce_perdue');
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
     * @Route("/ListerAnnonceTrouveAdmin" , name="annoncetrouve")
     */
    public function ListerAnnonceTrouveAdminAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy(['typeAnnonce'=>'annonce_trouve']);


        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Admin:annoncetrouve.html.twig', array(
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



    /**
     * @Route("/listerAnnoncePerduChien", name="afficherChien")
     */
    public function AfficherAnnoncePerduChienAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce perdu'
            ,'type'=>'chien'

        ]);


        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );



        return $this->render('AppBundle:Annonce:lister_annonce_perdue.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }

    /**
     * @Route("/listerAnnoncePerduChat", name="afficherChat")
     */
    public function AfficherAnnoncePerduChatAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce perdu',
            'type'=>'chat'

        ]);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_perdue.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }


    /**
     * @Route("/listerAnnoncePerduCheval", name="afficherCheval")
     */
    public function AfficherAnnoncePerduChevalAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce perdu',
            'type'=>'cheval'

        ]);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_perdue.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }

    /**
     * @Route("/listerAnnoncePerduChevre", name="afficherChevre")
     */
    public function AfficherAnnoncePerduChevreAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce perdu',
            'type'=>'chevre'

        ]);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_perdue.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }

    /**
     * @Route("/listerAnnoncePerduOiseau", name="afficherOiseau")
     */
    public function AfficherAnnoncePerduOiseauAction(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce perdu',
            'type'=>'oiseaux'

        ]);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_perdue.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }




/////////////////////////////////////////////////////////////////////////////






    /**
     * @Route("/listerAnnonceTrouveChien", name="afficherChien1")
     */
    public function AfficherAnnoncePerduChien1Action(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce_trouve'
            ,'type'=>'chien'

        ]);


        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );



        return $this->render('AppBundle:Annonce:lister_annonce_trouver.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }

    /**
     * @Route("/listerAnnoncetrouveChat", name="afficherChat1")
     */
    public function AfficherAnnoncePerduChat1Action(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce_trouve',
            'type'=>'chat'

        ]);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_trouver.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }


    /**
     * @Route("/listerAnnoncetrouveCheval", name="afficherCheval1")
     */
    public function AfficherAnnoncePerduCheval1Action(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce_trouve',
            'type'=>'cheval'

        ]);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_trouver.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }

    /**
     * @Route("/listerAnnoncetrouveChevre", name="afficherChevre1")
     */
    public function AfficherAnnoncePerduChevre1Action(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce_trouve',
            'type'=>'chevre'

        ]);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_trouver.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }

    /**
     * @Route("/listerAnnoncetrouveOiseau", name="afficherOiseau1")
     */
    public function AfficherAnnoncePerduOiseau1Action(Request $request)
    {

        $em=$this->getDoctrine()->getManager() ;
        $user = $this->getUser();
        $annonce=$em->getRepository("AppBundle:Annonce")->findBy([
            'typeAnnonce'=>'annonce_trouve',
            'type'=>'oiseaux'

        ]);

        /**
         * @var $paginator \Knp\Component\Pager\Paginator
         */

        $paginator= $this->get('knp_paginator') ;
        $annonce= $paginator->paginate(
            $annonce,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',4)

        );


        return $this->render('AppBundle:Annonce:lister_annonce_trouver.html.twig',array(

            'm'=>$annonce,
            'user' => $user
        ));

    }


    /**
     * @Route("/recherche", name="a_recherche")
     */
    public function rechercheAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $search = $request->get('search');
            dump($search);
            $annonce = new Annonce();
            $repo= $em->getRepository('AppBundle:Annonce');
            $annonce = $repo->findAnnonce($search);
            return $this->render('AppBundle:Annonce:search.html.twig'
                , array('m' => $annonce));
        }



    }

    /**
     * @Route("/recherche2", name="a_recherche2")
     */
    public function recherche2Action(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        if ($request->isXmlHttpRequest()) {
            $search = $request->get('search2');
            dump($search);
            $annonce = new Annonce();
            $repo= $em->getRepository('AppBundle:Annonce');
            $annonce = $repo->findAnnonce2($search);
            return $this->render('AppBundle:Annonce:search2.html.twig'
                , array('m' => $annonce));
        }



    }




    /**
     * @Route("/stat", name="stat")
     */
    public  function stataction()
    {
        $pieChart=new PieChart();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $annonceperdu = $em->getRepository('AppBundle:Annonce')->findBy(array('typeAnnonce'=> 'annonce perdu'));
        $annoncetrouve = $em->getRepository('AppBundle:Annonce')->findBy(array('typeAnnonce'=> 'annonce_trouve'));
        $nbr1 = 1;
        $nbr2 = 1 ;
        foreach ($annonceperdu as $annonceperdus)
        {
            $nbr1 = $nbr1 + 1 ;
        }
        foreach ($annoncetrouve as $annoncetrouves)
        {
            $nbr2 = $nbr2+1;
        }

        $pieChart->getData()->setArrayToDataTable(
            [['annonce', 'annonce'],
                ['annonce perdu',     $nbr1],
                ['annonce_trouve',      $nbr2],

            ]
        );

        $pieChart->getOptions()->setTitle('Pourcentages des Annonces Perdus et Trouvés');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);




        return $this->render('AppBundle:Admin:stat.html.twig', array(
            'piechart'=>$pieChart ,
            'user'=>$user
        ));
    }

    /**
     * @Route("/admin/supprimerAnnonceT" ,name="supprimerAnnonceT_admin")
     */
    public function DeleteAction(Request $request)
    {
        $id_offre = $request->get("keyword");
        $em=$this->getDoctrine()->getManager();
        $offre=$em->getRepository("AppBundle:Annonce")->find($id_offre);


        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute('annonce');

    }
    /**
     * @Route("/admin/supprimerAnnonceP" ,name="supprimerAnnonceP_admin")
     */
    public function Delete1Action(Request $request)
    {
        $id_offre = $request->get("keyword");
        $em=$this->getDoctrine()->getManager();
        $offre=$em->getRepository("AppBundle:Annonce")->find($id_offre);


        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute('annonceperdu');

    }


    //                                   mobile //////////////////////

    /**
     * @Route("/getAllAnnTR" ,name="getAll1")
     */
    public function GetAllAction()
    {
        $em=$this->getDoctrine()->getManager()->getRepository("AppBundle:Annonce")->findBy(['typeAnnonce'=>'annonce_trouve']);;
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize($em);
        return new JsonResponse($data) ;


    }




    /**
     * @Route("/AddAnnonceTrouver/new",name="add_annonce_trouver")
     */
    public function AjouterAnnonceTrouver2Action(Request $request)
    {

      //  if ($request->isMethod('post')) {

            $em = $this->getDoctrine()->getManager();

            $annonce = new Annonce();
            $annonce->setAge($request->get('age'));
            $annonce->setType($request->get('type'));
            $annonce->setSex($request->get('sexe'));
            $annonce->setRace($request->get('race'));

            $annonce->setCouleur($request->get('couleur'));
            $annonce->setColier($request->get('colier'));
          //  $annonce->setDateTrouvee(new \DateTime($request->get('date'))) ;

            $annonce->setLieuTrouve($request->get('lieu'));
            $annonce->setMessageComplementaire($request->get('message'));

            // $annonce->setDate($date->format("Y-m-d"));
        $utilisateur = $em->getRepository("AppBundle:Utilisateur")->find($request->get("user"));
           $annonce->setUtilisateur($utilisateur);

            $annonce->setTypeAnnonce("annonce_trouve");
             $annonce->setImages($request->get('image'));
              $em->persist($annonce);
            $em->flush();
            $serializer= new Serializer([new ObjectNormalizer()]) ;
            $data = $serializer->normalize( $annonce);
            return new JsonResponse($data) ;




    }




    /**
     * @Route("/modifierAnnonceTrouver3", name="modifier_annonce_trouver_3")
     */
    public function modifier3Action(Request $request)
    {
      //  $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $id = $request->get('id');
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);





            //$annonce->setImages($fileName);


          //  $annonce->setAge($request->get('age'));
      //      $annonce->setType($request->get('type'));
              $annonce->setSex($request->get('sexe'));
              $annonce->setRace($request->get('race'));
           $annonce->setCouleur($request->get('couleur'));

           // $annonce->setDateTrouvee(new \DateTime($request->get('date'))) ;
            $annonce->setLieuTrouve($request->get('lieu'));
            $annonce->setMessageComplementaire($request->get('message'));
            $em->persist($annonce);
            $em->flush();
            $serializer= new Serializer([new ObjectNormalizer()]) ;
            $data = $serializer->normalize( $annonce);
            return new JsonResponse($data) ;




        }


    /**
     * @Route("/listerAnnoncePerdu1/{id}", name="afficher_annonce_perdu_1")
     */
    public function AfficherAnnoncePerdu1Action($id)
    {

        $em=$this->getDoctrine()->getManager()->getRepository("AppBundle:Annonce")->findBy(['id'=>$id]);
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize($em);
        return new JsonResponse($data) ;



    }




 /**
  * @Route("/listerMesAnnonce1", name="afficher_mes_annonce_1")
  */
    public function AfficherMesAnnonceTrouver1Action(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getRepository("AppBundle:Annonce")
            ->findBy(
                [
                    'utilisateur'=>$this->get('security.token_storage')->getToken()->getUser(),
                    'typeAnnonce'=>'annonce_trouve'
                ]


            );



        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize($em);
        return new JsonResponse($data) ;




    }



    /**
     * @Route("/supprimerAnnonceTrouver_1", name="supprimer_annonce_trouver_1")
     */
    public function supprimer9Action(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);
         $em->remove($annonce);
         $em->flush();
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize( $annonce);
        return new JsonResponse($data) ;




    }

// Annonce Perdu Mobile ////




    /**
     * @Route("/getAllAnnPR" ,name="getAll56")
     */
    public function GetAll56Action()
    {
        $em=$this->getDoctrine()->getManager()->getRepository("AppBundle:Annonce")->findBy(['typeAnnonce'=>'annonce perdu']);;
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize($em);
        return new JsonResponse($data) ;


    }




    /**
     * @Route("/AddAnnoncePR/new",name="add_annonce_perdu")
     */
    public function AjouterAnnoncePerdu56Action(Request $request)
    {

        //  if ($request->isMethod('post')) {

        $em = $this->getDoctrine()->getManager();

        $annonce = new Annonce();
        $annonce->setAge($request->get('age'));
        $annonce->setType($request->get('type'));
        $annonce->setSex($request->get('sexe'));
        $annonce->setRace($request->get('race'));

        $annonce->setCouleur($request->get('couleur'));
        $annonce->setColier($request->get('colier'));
        //  $annonce->setDateTrouvee(new \DateTime($request->get('date'))) ;

        $annonce->setLieuPerdu($request->get('lieu'));
        $annonce->setMessageComplementaire($request->get('message'));

        // $annonce->setDate($date->format("Y-m-d"));
        $utilisateur = $em->getRepository("AppBundle:Utilisateur")->find($request->get("user"));
        $annonce->setUtilisateur($utilisateur);

        $annonce->setTypeAnnonce("annonce perdu");
        $annonce->setImages($request->get('image'));
        $em->persist($annonce);
        $em->flush();
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize( $annonce);
        return new JsonResponse($data) ;




    }




    /**
     * @Route("/modifierAnnoncePerdu56", name="modifier_annonce_Perdu_56")
     */
    public function modifier56Action(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $id = $request->get('id');
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);
        $annonce->setSex($request->get('sexe'));
        $annonce->setRace($request->get('race'));
        $annonce->setCouleur($request->get('couleur'));
         $annonce->setLieuPerdu($request->get('lieu'));
        $annonce->setMessageComplementaire($request->get('message'));
        $em->persist($annonce);
        $em->flush();
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize( $annonce);
        return new JsonResponse($data) ;




    }


    /**
     * @Route("/listerAnnoncePerdu56/{id}", name="afficher_annonce_perdu_56")
     */
    public function AfficherAnnoncePerdu56Action($id)
    {

        $em=$this->getDoctrine()->getManager()->getRepository("AppBundle:Annonce")->findBy(['id'=>$id]);
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize($em);
        return new JsonResponse($data) ;



    }




    /**
     * @Route("/listerMesAnnonce56", name="afficher_mes_annonce_56")
     */
    public function AfficherMesAnnonceTrouver56Action(Request $request)
    {

        $em = $this->getDoctrine()->getManager()->getRepository("AppBundle:Annonce")
            ->findBy(
                [
                    'utilisateur'=>$this->get('security.token_storage')->getToken()->getUser(),
                    'typeAnnonce'=>'annonce_trouve'
                ]


            );



        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize($em);
        return new JsonResponse($data) ;




    }



    /**
     * @Route("/supprimerAnnoncePerdu_56", name="supprimer_annonce_trouver_56")
     */
    public function supprimer56Action(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $annonce = new Annonce();
        $annonce = $em->getRepository('AppBundle:Annonce')->find($id);
        $em->remove($annonce);
        $em->flush();
        $serializer= new Serializer([new ObjectNormalizer()]) ;
        $data = $serializer->normalize( $annonce);
        return new JsonResponse($data) ;




    }



}
