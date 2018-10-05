<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
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
        $em      = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($request->get('product_id'));
        $user    = $this->getUser();



        $cart = new Cart();
        $cart
            ->setUser($user)
            ->setProduct($product)
            ->setAmount(1)
        ;
        $em->persist($cart);
        $em->flush();

        $cart   = $em->getRepository(Cart::class)->getCartByUser($user->getId());

        $result = [
          'message'       => 'Товар добавлен в корзину',
          'cart_message'  => count($cart),
          'error_message' => '',
        ];

        return new JsonResponse($result);
    }


    /**
     * @Route("/header", name="cart_header")
     */
    public function header()
    {
        $items = 0;

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')){
            $em    = $this->getDoctrine()->getManager();
            $cart  = $em->getRepository(Cart::class)->getCartByUser($this->getUser()->getId());
            $items = count($cart);
        }

        return $this->render('cart/header.html.twig', [
            'items' => $items,
        ]);
    }
}
