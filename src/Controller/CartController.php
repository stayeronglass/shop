<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/cart", name="cart_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class CartController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $em      = $this->getDoctrine()->getManager();
        $user    = $this->getUser();
        $cart   = $em->getRepository(Cart::class)->getFullCartByUser($user->getId());

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, TranslatorInterface $translator)
    {
        $em      = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($request->get('product_id'));
        $user    = $this->getUser();



        $cart = new Cart();
        $cart
            ->setUser($user)
            ->setProduct($product)
            ->setAmount($request->get('amount', 1))
        ;
        $em->persist($cart);
        $em->flush();

        $cart   = $em->getRepository(Cart::class)->getCartByUser($user->getId());

        $result = [
          'message'       => 'Товар добавлен в корзину',
          'cart_message'  => count($cart) .' '. $translator->transChoice('some.translation.key', count($cart) ).' '. $translator->trans('in cart'),
          'error_message' => '',
        ];

        return new JsonResponse($result);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id){

        $em   = $this->getDoctrine()->getManager();
        $cart = $em->getRepository(Cart::class)->findOneBy(['id' => $id, 'user_id' => $this->getUser()->getId()]);

        if ($cart){
            $em->remove($cart);
            $em->flush();
        }

        $result = [
        ];

        return new JsonResponse($result);
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout(Request $request){

        return $this->render('cart/checkout.html.twig',[

        ]);
    }
}
