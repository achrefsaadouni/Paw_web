<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class AchatController extends Controller
{
    /**
     * @Route("/admin/gererachat" ,name="gererachat_admin")
     */
    public function gererAchatAction(Request $request) {
        $user = $this->getUser();
        return $this->render('AppBundle:admin:achat.html.twig',array(
            'user' => $user,
        ));
    }
}
