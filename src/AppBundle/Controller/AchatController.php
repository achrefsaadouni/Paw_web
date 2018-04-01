<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AchatController extends Controller
{
    /**
     * @Route("/admin/afficheAchat" ,name="afficheAchat")
     */
    public function AfficheAction(Request $request)
    {
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $achats=$em->getRepository("AppBundle:Achat")->findAll();

        return $this->render('AppBundle:admin:afficheAchat.html.twig', array(
            'achats'=>$achats,
            'user' => $user,
        ));
    }

    /**
     * @Route("/admin/supprimerAchat" ,name="supprimerAchat")
     */
    public function DeleteAction(Request $request)
    {
        $id_achat = $request->get("keyword");
        $em=$this->getDoctrine()->getManager();
        $achat=$em->getRepository("AppBundle:Achat")->find($id_achat);
        $ligne=$em->getRepository("AppBundle:Ligneachat")->removeAll($id_achat);

        $em->remove($achat);
        $em->flush();
        return $this->redirectToRoute('afficheAchat');

    }
    /**
     * @Route("/admin/livreAchat" ,name="livreAchat")
     */
    public function LivreAction(Request $request)
    {
        $id_achat = $request->get("keyword");
        $em=$this->getDoctrine()->getManager();
        $achat=$em->getRepository("AppBundle:Achat")->find($id_achat);

       $achat->setEtat('livré');

        $em->flush();
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $achats=$em->getRepository("AppBundle:Achat")->findAll();
        return $this->render('AppBundle:admin:affiche2Achat.html.twig', array(
            'achats'=>$achats,
            'user' => $user,
        ));

    }


    /**
     * @Route("/admin/consulterAchat/{id}" ,name="consulterAchat")
     */
    public function ConsulterAction($id,Request $request)
    {
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $ligneAchats=$em->getRepository("AppBundle:Ligneachat")->findBy(array('idAchat' =>$id));
        return $this->render('AppBundle:admin:consulterAchat.html.twig', array(
            'user' => $user,
            'ligneAchats' => $ligneAchats
        ));

    }


}
