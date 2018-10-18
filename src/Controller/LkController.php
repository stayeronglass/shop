<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/my", name="my_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class LkController extends Controller
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        return $this->render('lk/index.html.twig', [
            'controller_name' => 'LkController',
        ]);
    }


    /**
     * @Route("/orders", name="orders")
     */
    public function orders()
    {
        return $this->render('lk/orders.html.twig', [
        ]);
    }


    /**
     * @Route("/messages", name="messages")
     */
    public function messages()
    {
        return $this->render('lk/messages.html.twig', [
        ]);
    }


    /**
     * @Route("/addresses", name="addresses")
     */
    public function addresses()
    {
        return $this->render('lk/addresses.html.twig', [
        ]);
    }


    /**
     * @Route("/account", name="account")
     */
    public function personal(){
        return $this->render('lk/personal.html.twig', [
        ]);
    }

    public function header()
    {
        return $this->render('lk/header.html.twig', [
        ]);
    }

    public function footer()
    {
        return $this->render('lk/footer.html.twig', [
        ]);

    }

    public function right()
    {
        return $this->render('lk/right.html.twig', [
        ]);
    }
}
