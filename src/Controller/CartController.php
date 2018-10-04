<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'BasketController',
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request)
    {
        $product_id  = $request->get('product_id');
        return $this->json($product_id);
    }


    /**
     * @Route("/header", name="cart_header")
     */
    public function header()
    {
        $items = 0;
        return $this->render('cart/header.html.twig', [
            'items' => $items,
        ]);
    }
}
