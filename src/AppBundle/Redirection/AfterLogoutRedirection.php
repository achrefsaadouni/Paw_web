<?php
/**
 * Created by PhpStorm.
 * User: Meedoch
 * Date: 18/02/2017
 * Time: 15:04
 */

namespace AppBundle\Redirection;



use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
class AfterLogoutRedirection implements LogoutSuccessHandlerInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;


    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    /**
     * @Route("/logout", name="logout")
     * @param Request $request
     * @return RedirectResponse
     */
    public function onLogoutSuccess(Request $request)
    {
        $response= new RedirectResponse($this->router->generate("home_member"));
        return $response;
    }
}