<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Panier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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


    /**
     * @Route("/client/boutique/panier" ,name="panier")
     */
    public function CartAction(Request $request)
    {
        $user = $this->getUser();
        $em=$this->getDoctrine()->getManager();
        $session = $request->getSession();
        $panier = $session->has("panier") ? $session->get("panier") : new Panier();
        return $this->render('AppBundle:Membre:cart.html.twig', array(
            'user' => $user,
            'panier' => $panier
        ));

    }

    /**
     * @Route("/client/boutique/viderPanier" ,name="viderPanier")
     */
    public function resetAction(Request $request)
    {
        $user = $this->getUser();
        $session = $request->getSession();
        $session->remove("panier");
        return new Response("OK");
    }

    /**
     * @Route("/client/boutique/article/panier/enlever/{id}", name="removeArticle")
     */
    public function supprimerArticleDuPanierAction(Request $request, $id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $article = $em->getRepository("AppBundle:Produit")->find($id);
        $panier = $session->get("panier");
        $panier->removeItem($article);
        return $this->redirectToRoute("panier");
    }


    /**
     * @Route("/client/boutique/article/acheter", name="acheterProduit")
     */
    public function buyAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $id=$request->get('keyword');
        $session = $request->getSession();
        $article = $em->getRepository("AppBundle:Produit")->find($id);
        if ( empty($session->get("panier")) ) {
                $panier = new Panier();
                $panier->addItem($article);
                $session->set("panier", $panier);

        } else {
                $panier = $session->get("panier");
                $panier->addItem($article);
        }

        return $this->redirectToRoute("panier");
    }

    /**
     * @Route("/client/boutique/article/panier/plus/{id}", name="plus")
     */
    public function plusAction(Request $request, $id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $article = $em->getRepository("AppBundle:Produit")->find($id);
        $panier = $session->get("panier");
        $panier->plus($article);
        return $this->redirectToRoute("panier");
    }
    /**
     * @Route("/client/boutique/article/panier/minus/{id}", name="minus")
     */
    public function minusAction(Request $request, $id)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $article = $em->getRepository("AppBundle:Produit")->find($id);
        $panier = $session->get("panier");
        $panier->minus($article);
        return $this->redirectToRoute("panier");
    }

}
