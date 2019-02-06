<?php

namespace App\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\User;

class RedirectUserListener
{
    private $tokenStorage;
    private $router;


    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router       = $router;
    }


    public function onKernelRequest( $event)
    {
        if ($this->isUserLogged() && $event->isMasterRequest()) {

            $currentRoute = $event->getRequest()->attributes->get('_route');

            if ($this->isAnonymousPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('my_main'));
                $event->setResponse($response);
            }
        }
    }


    private function isUserLogged()
    {
        $token = $this->tokenStorage->getToken();

        return $token && ($token->getUser() instanceof User);
    }


    private function isAnonymousPage($currentRoute)
    {
        return \in_array(
            $currentRoute,
            ['fos_user_security_login', 'fos_user_resetting_request', 'app_user_registration']
        );
    }
}
