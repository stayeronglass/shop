<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/cart")
 */
class CartController extends Controller
{
    /**
     * @Route("/", name="cart")
     */
    public function index()
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'BasketController',
        ]);
    }


    /**
     * @Route("/header", name="cart_header")
     */
    public function header()
    {
        return $this->render('cart/header.html.twig', [
        ]);
    }

    public function add(Request $request){}
    public function addAnonymous(Request $request){}

}
