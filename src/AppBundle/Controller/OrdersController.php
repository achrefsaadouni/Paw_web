<?php
/**
 * Created by PhpStorm.
 * User: achref
 * Date: 09/04/2018
 * Time: 18:36
 */

// src/AppBundle/Controller/OrdersController.php

namespace AppBundle\Controller;

use AppBundle\Entity\Panier;
use JMS\Payment\CoreBundle\Plugin\Exception\ActionRequiredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Order;
use JMS\Payment\CoreBundle\Form\ChoosePaymentMethodType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use JMS\Payment\CoreBundle\PluginController\Result;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use JMS\Payment\CoreBundle\Plugin\Exception\Action\VisitUrl;

class OrdersController extends Controller
{
    public $email = 'vingarde-paw@live.fr';

    /**
     * @Route("/new/{amount}" , name="order")
     */
    public function newAction($amount)
    {
        $em = $this->getDoctrine()->getManager();

        $order = new Order($amount);
        $em->persist($order);
        $em->flush();
        return $this->redirectToRoute('showPaypal', array('id' =>$order->getId()), 301);

    }

    /**
     * @Route("client/payer/show",name="showPaypal")
     *
     */
    public function showAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $order =  $em->getRepository("AppBundle:Order")->find($id);

        $config = [
            'paypal_express_checkout' => [
                'return_url' => $this->generateUrl('app_orders_paymentcomplete', [
                    'id' => $order->getId(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
                'cancel_url' => $this->generateUrl('home_member', [
                    'id' => $order->getId(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
                'notify_url' => $this->generateUrl('home_member', [
                    'id' => $order->getId(),
                ], UrlGeneratorInterface::ABSOLUTE_URL),
                'useraction' => 'commit',
            ],
        ];

        $form = $this->createForm(ChoosePaymentMethodType::class, null, [
            'amount'          => $order->getAmount(),
            'currency'        => 'USD',
            'predefined_data' => $config,
            'default_method'  => 'paypal_express_checkout',

        ]);
        $form->handleRequest($request);

            //$form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $ppc = $this->get('payment.plugin_controller');
                $ppc->createPaymentInstruction($instruction = $form->getData());

                $order->setPaymentInstruction($instruction);

                $em = $this->getDoctrine()->getManager();
                $em->persist($order);
                $em->flush($order);

                return $this->redirect($this->generateUrl('app_orders_paymentcreate', [
                    'id' => $order->getId(),
                ]));
            }

            return $this->render('@App/Membre/paypal.html.twig',[
                'order' => $order,
                'form'  => $form->createView(),
            ]);
        }



    // src/AppBundle/Controller/OrdersController.php

    private function createPayment($order)
    {
        $instruction = $order->getPaymentInstruction();
        $pendingTransaction = $instruction->getPendingTransaction();

        if ($pendingTransaction !== null) {
            return $pendingTransaction->getPayment();
        }

        $ppc = $this->get('payment.plugin_controller');
        $amount = $instruction->getAmount() - $instruction->getDepositedAmount();

        return $ppc->createPayment($instruction->getId(), $amount);
    }


    /**
     * @Route("/{id}/payment/create")
     */
    public function paymentCreateAction(Order $order)
    {
        $payment = $this->createPayment($order);

        $ppc = $this->get('payment.plugin_controller');
        $result = $ppc->approveAndDeposit($payment->getId(), $payment->getTargetAmount());

        if ($result->getStatus() === Result::STATUS_SUCCESS) {
            return $this->redirect($this->generateUrl('app_orders_paymentcomplete', [
                'id' => $order->getId(),
            ]));
        }
        if ($result->getStatus() === Result::STATUS_PENDING) {
            $ex = $result->getPluginException();

            if ($ex instanceof ActionRequiredException) {
                $action = $ex->getAction();

                if ($action instanceof VisitUrl) {
                    return $this->redirect($action->getUrl());
                }
            }
        }
        throw $result->getPluginException();

        // In a real-world application you wouldn't throw the exception. You would,
        // for example, redirect to the showAction with a flash message informing
        // the user that the payment was not successful.
    }
    /**
     * @Route("/{id}/payment/complete")
     */
    public function paymentCompleteAction(Request $request,Order $order)
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $session = $request->getSession();
        $panier = $session->has("panier") ? $session->get("panier") : new Panier();
        if($panier->getTotal()<30)
        {
            $panier->setTotal($panier->getTotal()+5);
        }
        $panier->payer("Payer",$user,$em);
        $session->remove("panier");
        $session->set('panier',new Panier());
        return $this->redirect('https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_xclick&business='.$this->email.'&tax=0&currency=USD&item_name=test&item_number=1&quantity=1&amount='.$order->getAmount());
    }

}