<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Repadoption;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use AppBundle\Entity\Annonce;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;


class AdoptionController extends Controller
{
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }

    /**
     * @Route("/AdoptionAjout" ,name="AdoptionAjout_membre")
     */
    public function newAction(Request $request)
    {
        $user = $this->getUser();
        // create a task and give it some dummy data for this example
        $adoption = new Annonce();

        $form = $this->createFormBuilder($adoption)
            ->add('type', ChoiceType::class, array(
                'choices'  => array(
                    'Chien' => 'Chien',
                    'Chat' => 'Chat',
                    'Rongeur' => 'Rongeur',
                    'Poisson' => 'Poisson',
                    'Oiseau' => 'Oiseau',
                ),
            ))
            ->add('age', IntegerType::class)
            ->add('sex', ChoiceType::class, array(
                'choices' => array('Male' => 'Male', 'Female' => 'Female'),
                'expanded' => true,
            ))
            ->add('couleur', TextType::class)
            ->add('messageComplementaire', TextareaType::class)
            ->add('race', TextType::class)
            ->add('typeadoption', ChoiceType::class, array(
                'choices' => array('Temporaire' => 'Temporaire', 'Permanante' => 'Permanante'),
            ))
            ->add('debutadoption', DateType::class)
            ->add('finadoption', DateType::class)
            ->add('images',FileType::class)
            ->add('save', SubmitType::class, array('label' => 'Poster'))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values

            $adoption = $form->getData();
            $adoption->setUtilisateur($user);
            $adoption->setTypeAnnonce('Annonce_adoption');
            $adoption->setEtatadoption('Disponible');

            /** @var Symfony\Component\HttpFoundation\File\UploadedFile $file1 */
            $file1 = $adoption->getImages();
            $fileName1 = $this->generateUniqueFileName().'.'.$file1->guessExtension();
            $file1->move(
                $this->getParameter('adoption_directory'),
                $fileName1
            );

            $adoption->setDate(new \DateTime('now', (new \DateTimeZone('Africa/Tunis'))));
            // ... perform some action, such as saving the task to the database
            // for example, if Task is a Doctrine entity, save it!
            $em = $this->getDoctrine()->getManager();
            $adoption->setImages($fileName1);
            $em->persist($adoption);
            $em->flush();

            return $this->redirectToRoute('home_member');
        }
        return $this->render('AppBundle:Ayoub:AjoutAdoption.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }

    /**
     * @Route("/MesOffresAdoption" ,name="MesOffresAdoption_membre")
     */
    public function listAction(Request $request)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT a
        FROM AppBundle:Annonce a
        WHERE a.utilisateur = :iduser
        AND a.typeAnnonce = :t'
        )->setParameter('iduser', $this->getUser())
            ->setParameter('t', 'Annonce_adoption');

        $offres = $query->getResult();
        foreach ($offres as $value)
        {

            $value->reps = $em->getRepository('AppBundle:Repadoption')->findBy((array('idAnnonce' => $value->getId())));
        }


        // parameters to template
        return $this->render('AppBundle:Ayoub:afficheMesOffresAdoption.html.twig', array('pagination' => $offres,'user' => $user));
    }

    /**
     * @Route("/LesAdoptionsDisponible" ,name="LesAdoptionsDisponible_membre")
     */
    public function DisponibleAction(Request $request)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT a
            FROM AppBundle:Annonce a
             WHERE a.utilisateur != :iduser
            AND a.typeAnnonce = :t
            AND a.etatadoption = :d'
        )->setParameter('iduser', $this->getUser())
            ->setParameter('t', 'Annonce_adoption')
            ->setParameter('d', 'Disponible');
        $i=0;
        $offres = $query->getResult();
        foreach ($offres as $value)
        {
            $i=$i+1;
            $value->rep = $em->getRepository('AppBundle:Repadoption')->findOneBy((array('idAnnonce' => $value->getId(),'idUtilisateur' => $this->getUser())));
        }
        /**
         * @var $paginator \knp\Component\Pager\Paginator
         */
        $paginator  = $this->get('knp_paginator');
        $results=$paginator-> paginate(
            $query,
            $request->query->getInt('page',1),
            $request->query->getInt('limit',100)
        );

        // parameters to template
        return $this->render('AppBundle:Ayoub:afficheAdoptionDisponible.html.twig', array('pagination' => $results,'user' => $user,'j' => $i));
    }




    /**
     * @Route("/EnregistrerReponseAdoption/{id}" ,name="EnregistrerAdoption_membre")
     */
    public function EnreRepAction(Request $request,$id)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository('AppBundle:Annonce')->findOneBy((array('id' => $id,'typeAnnonce' => 'Annonce_adoption')));
        $testRep = $em->getRepository('AppBundle:Repadoption')->findOneBy((array('idAnnonce' => $id,'idUtilisateur' => $this->getUser())));
        if($annonce and !$testRep)
        {
            $rep=new Repadoption();
            $rep->setIdUtilisateur($user);
            $rep->setIdAnnonce($annonce);
            $rep->setDate(new \DateTime('now', (new \DateTimeZone('Africa/Tunis'))));
            $rep->setEtat('Non confirmée');
            $em->persist($rep);
            $em->flush();
        }

        // parameters to template
        return $this->redirectToRoute('LesAdoptionsDisponible_membre');
    }


    /**
     * @Route("/voirDemandeAdoption/{id}" ,name="voirDemandeAdoption_membre")
     */
    public function VoirDemandeAction(Request $request,$id)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository('AppBundle:Annonce')->findOneBy((array('id' => $id,'typeAnnonce' => 'Annonce_adoption')));
        $reps = $em->getRepository('AppBundle:Repadoption')->findBy(array('idAnnonce' => $id));
        // parameters to template
        return $this->render('AppBundle:Ayoub:VoirReponsesAnnonce.html.twig', array('reponses'=>$reps,'user' => $user,'annonce'=>$annonce));
    }

    /**
     * @Route("/accepterReponse/{id}/{ida}" ,name="accepterReponse_membre")
     */
    public function AccepterReponseAction(Request $request,$id,$ida)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $reponse=new Repadoption();
        $annonce=new Annonce();
        $reponse = $em->getRepository('AppBundle:Repadoption')->findOneBy(array('id' => $id));
        $annonce = $em->getRepository('AppBundle:Annonce')->findOneBy((array('id' => $ida)));
        $reponse->setEtat('Confirmée');
        $annonce->setEtatadoption('Non Disponible');
        $em->flush();
        return $this->redirectToRoute('MesOffresAdoption_membre');
        // parameters to template
    }

    /**
     * @Route("/voirAcceptation/{id}" ,name="voirAcceptation_membre")
     */
    public function VoirAcceptationAction(Request $request,$id)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository('AppBundle:Annonce')->findOneBy((array('id' => $id,'typeAnnonce' => 'Annonce_adoption')));
        $reponse = $em->getRepository('AppBundle:Repadoption')->findOneBy(array('idAnnonce' => $id,'etat' => 'Confirmée'));
        // parameters to template
        return $this->render('AppBundle:Ayoub:VoirAcceptationAnnonce.html.twig', array('reponse'=>$reponse,'user' => $user,'annonce'=>$annonce));
    }

    /**
     * @Route("/admin/supprimerOffreAdoption" ,name="supprimerOffreAdoption_admin")
     */
    public function DeleteAction(Request $request)
    {
        $id_offre = $request->get("keyword");
        $em=$this->getDoctrine()->getManager();
        $offre=$em->getRepository("AppBundle:Annonce")->find($id_offre);


        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute('afficheAdoption_admin');

    }



    /**
     * @Route("/admin/LesAdoptionsDisponible" ,name="afficheAdoption_admin")
     */
    public function DisponibleAdminAction(Request $request)
    {
        $user = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT a
            FROM AppBundle:Annonce a
            WHERE a.typeAnnonce = :t
            AND a.etatadoption = :d'
        )->setParameter('t', 'Annonce_adoption')
            ->setParameter('d', 'Disponible');

        $offres = $query->getResult();
        foreach ($offres as $value)
        {

            $value->reponses = $em->getRepository('AppBundle:Repadoption')->findBy((array('idAnnonce' => $value->getId())));
        }

        // parameters to template
        return $this->render('AppBundle:AyoubAdmin:afficheAdoptionDisponible.html.twig', array('liste' => $offres,'user' => $user));
    }

    /**
     * @Route("/admin/statAdoption", name="statAdoption_admin")
     */
    public  function stataction()
    {
        $pieChart=new BarChart();
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $dispo = $em->getRepository('AppBundle:Annonce')->findBy(array('typeAnnonce'=> 'Annonce_adoption','etatadoption'=> 'Disponible'));
        $ndispo = $em->getRepository('AppBundle:Annonce')->findBy(array('typeAnnonce'=> 'Annonce_adoption','etatadoption'=> 'Non Disponible'));
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
            [['Offres dispo', 'Offres non dispo'],
                ['Offres disponibles',     $nbr1],
                ['Offres non disponibles',      $nbr2],

            ]
        );

        $pieChart->getOptions()->setTitle('Etat des offres adoption');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);




        return $this->render('AppBundle:AyoubAdmin:statAdop.html.twig', array(
            'piechart'=>$pieChart ,
            'user'=>$user
        ));
    }
    /**
     * @Route("/rechercheAdoption1", name="offre_recherche")
     */
    public function rechercheAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();

        $keyword = $request->get("search");
        $em = $this->getDoctrine()->getManager();
        $annonce = $em->getRepository('AppBundle:Annonce')->findRech($keyword);
        $i=0;
        foreach ($annonce as $value)
        {
            $i=$i+1;
            $value->rep = $em->getRepository('AppBundle:Repadoption')->findOneBy((array('idAnnonce' => $value->getId(),'idUtilisateur' => $this->getUser(),'idUtilisateur' => $this->getUser())));
        }
            return $this->render('AppBundle:Ayoub:search.html.twig'
                , array('pagination' => $annonce,'j' => $i,'user'=>$user));



    }
}
