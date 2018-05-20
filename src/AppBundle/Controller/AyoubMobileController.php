<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use AppBundle\Entity\Reclamation;

class AyoubMobileController extends Controller
{
    /**
     * @Route("/api/Connexion")
     */
    public function ConnexionAction()
    {
        $liste = $this->getDoctrine()->getManager()
            ->getRepository('AppBundle:Utilisateur')->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($liste);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/api/Inscription")
     */
    public function InscriptionAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $u = new Utilisateur();
        $u->setNom($request->get('nom'));
        $u->setPrenom($request->get('prenom'));
        $u->setAddresse($request->get('adresse'));
        $u->setEmail($request->get('email'));
        $u->setSexe($request->get('sexe'));
        $u->setNumero($request->get('numero'));
        $u->setAvatar($request->get('avatar'));
        $u->setPassword($request->get('password'));
        $u->setUsername($request->get('username'));
        $u->setEnabled(true);
        $em->persist($u);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($u);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/api/MiseAjourUtilisateur")
     */
    public function MiseAjourUtilisateurAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $u = $em->getRepository('AppBundle:Utilisateur')->find($request->get('id'));
        $u->setNom($request->get('nom'));
        $u->setPrenom($request->get('prenom'));
        $u->setAddresse($request->get('adresse'));
        $u->setEmail($request->get('email'));
        $u->setSexe($request->get('sexe'));
        $u->setNumero($request->get('numero'));
        $u->setAvatar($request->get('avatar'));
        $u->setPassword($request->get('password'));
        $u->setUsername($request->get('username'));
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($u);
        return new JsonResponse($formatted);
    }


    /**
     * @Route("/api/AjoutFeedBack")
     */
    public function AjouReclamationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $u = new Reclamation();
        $u->setObjet($request->get('objet'));
        $u->setText($request->get('text'));
        $u->setType($request->get('type'));
        $utilisa= $this->getDoctrine()->getManager()->getRepository('AppBundle:Utilisateur')->find($request->get('utilisateur'));
        $u->setIdUtilisateur($utilisa);
        $u->setEtat('Non traitée');
        $u->setDate(new \DateTime('now', (new \DateTimeZone('Africa/Tunis'))));
        $em->persist($u);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($u);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/api/Reclamation/{id}" ,name="ReclamationMobile")
     */
    public function ReclamationAction($id)
    {
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT a
        FROM AppBundle:Reclamation a
        WHERE a.idUtilisateur = :iduser'
        )->setParameter('iduser', $id);

        $results=$query->getResult();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($results);
        return new JsonResponse($formatted);
    }


    /**
     * @Route("/api/RepRec/{id}" ,name="RepReclamationMobile")
     */
    public function RepReclamationAction($id)
    {
        $em    = $this->getDoctrine()->getManager();
        $query = $em->createQuery(
            'SELECT a
        FROM AppBundle:Reponsereclamation a
        WHERE a.idReclamation = :idu'
        )->setParameter('idu', $id);

        $results=$query->getResult();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($results);
        return new JsonResponse($formatted);
    }

}
