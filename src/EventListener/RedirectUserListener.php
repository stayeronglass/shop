<?php

namespace App\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Model\User;
use Symfony\Component\HttpKernel\Event\RequestEvent;


class RedirectUserListener
{
    private $tokenStorage;
    private $router;


    public function __construct(TokenStorageInterface $tokenStorage, RouterInterface $router)
    {
        $this->tokenStorage = $tokenStorage;
        $this->router       = $router;
    }


    public function onKernelRequest(RequestEvent $event)
    {
        if ($event->isMasterRequest() && $this->isUserLogged()) {

            $currentRoute = $event->getRequest()->attributes->get('_route');

            if ($this->isAnonymousPage($currentRoute)) {
                $response = new RedirectResponse($this->router->generate('my_main'));
                $event->setResponse($response);
            }
        }

        return;
    }


    private function isUserLogged() : bool
    {
        $token = $this->tokenStorage->getToken();

        return $token && ($token->getUser() instanceof User);
    }


    private function isAnonymousPage($currentRoute) : bool
    {
        $pages = ['fos_user_security_login' => true, 'fos_user_resetting_request' => true, 'app_user_registration' => true];

        return $pages[$currentRoute] ?? false;
    }
}
