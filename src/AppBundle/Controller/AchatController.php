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
     * @Route("/client/consultermesAchat" ,name="consultermesAchat")
     */
    public function AfficheMesAchatAction(Request $request)
    {
        $em=$this->getDoctrine()->getManager();
        $achats=$em->getRepository("AppBundle:Achat")->findBy(array('idClient' => $this->getUser()->getId()));

        return $this->render('AppBundle:Membre:afficherMesAchat.html.twig', array(
            'achats'=>$achats,
        ));
    }

    /**
     * @Route("/client/consulterdetailAchat?id={id}" ,name="consulterdetailAchat")
     */
    public function ConsulterAchatAction(Request $request,$id)
    {
        $em=$this->getDoctrine()->getManager();
        $ligne=$em->getRepository("AppBundle:Ligneachat")->findBy(array('idAchat' => $id));

        return $this->render('AppBundle:Membre:consulterAchat.html.twig', array(
            'lignes'=>$ligne,
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
     * @Route("/client/boutique/article/panier/enlever", name="removeArticle")
     */
    public function supprimerArticleDuPanierAction(Request $request)
    {
        $id = $request->get('id');
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $article = $em->getRepository("AppBundle:Produit")->find($id);
        $panier = $session->get("panier");
        $panier->removeItem($article);
        return $this->render('AppBundle:Membre:cart_ajax.html.twig', array(
            'panier' => $panier
        ));
    }


    /**
     * @Route("/client/boutique/acheter", name="acheterProduit")
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
     * @Route("/client/boutique/panier/plus", name="plus")
     */
    public function plusAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $article = $em->getRepository("AppBundle:Produit")->find($id);
        $panier = $session->get("panier");
        $panier->plus($article);
        return $this->render('AppBundle:Membre:cart_ajax.html.twig', array(
            'panier' => $panier
        ));
    }
    /**
     * @Route("/client/boutique/article/panier/minus", name="minus")
     */
    public function minusAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $article = $em->getRepository("AppBundle:Produit")->find($id);
        $panier = $session->get("panier");
        $panier->minus($article);
        return $this->render('AppBundle:Membre:cart_ajax.html.twig', array(
            'panier' => $panier
        ));
    }

    /**
 * @Route("/client/boutique/commander", name="commander")
 */
    public function CommanderAction()
    {


        return $this->render('AppBundle:Membre:methodepaiement.html.twig', array(

        ));

    }

    /**
     * @Route("/client/boutique/payer", name="payer")
     */
    public function payerAction(Request $request)
    {

        $methode = $request->get("methode");
        if($methode==('livraison'))
        {
            $session = $request->getSession();
            if($session->has("code"))
            {
               $code= $session->get("code");
            }
            else
            {
                $code =$this->generateUniqueFileName();
                $session->set('code',$code);
                $this->sendmail($code);
            }
            return $this->render('AppBundle:Membre:livraison.html.twig', array(
                'code' => $code,
                'panier' => $request->getSession()->get("panier")
            ));
        }

    }

    /**
     * @Route("/client/boutique/reenvoyer" ,name="recode")
     */
    public function reenevoyerAction(Request $request)
    {
        $session = $request->getSession();
        $code =$this->generateUniqueFileName();
        $session->set('code',$code);
        $this->sendmail($code);
        return new Response($code);
    }

    public function sendmail($code)
    {

        $mailer = $this->container->get('mailer');
        $transporter = \Swift_SmtpTransport::newInstance ('smtp.gmail.com',465,'ssl')
            ->setUsername('pawzcorporation@gmail.com')
            ->setPassword('pawz0000');
        $mailer = \Swift_Mailer::newInstance($transporter);
        $message = \Swift_Message::newInstance('Verification')
            ->setSubject('Confirmation Du Paiement')
            ->setFrom('pawzcorporation@gmail.com')
            ->setTo($this->getUser()->getEmail())
            ->setBody("Pour Confimer votre Commande veuillez Utiliser ce code : " .$code . "\n NB: ce Code est a usage unique");
        $mailer->send($message);
    }


    /**
     * @Route("/client/boutique/confirmer" ,name="confirmerlivraison")
     */
    public function confirmerLivraisonrAction(Request $request)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $panier = $session->has("panier") ? $session->get("panier") : new Panier();
        if($panier->getTotal()==0)
        {
            return $this->redirectToRoute("panier");
        }
       else
       {
           if($panier->getTotal()<30)
           {
               $panier->setTotal($panier->getTotal()+5);
           }
           $panier->payer("Non Payer",$user,$em);
           $session->set('panier',new Panier());
           $session->remove('code');
       }

        return $this->redirectToRoute("home_member");
    }
    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}
