<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
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
    public function index(TranslatorInterface $translator)
    {
        $em   = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $cart = $em->getRepository(Cart::class)->getFullCartByUser($user->getId());


        return $this->render('cart/index.html.twig', [
            'cart'  => $cart,
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
          'alert_message'       => 'Товар добавлен в корзину',
          'cart_message'  => count($cart) .' '. $translator->transChoice('some.translation.key', count($cart) ).' '. $translator->trans('in cart'),
          'error_message' => '',
        ];

        return new JsonResponse($result);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id, TranslatorInterface $translator)
    {
        $em   = $this->getDoctrine()->getManager();
        $cart = $em->getRepository(Cart::class)->findOneBy(['id' => $id, 'user_id' => $this->getUser()->getId()]);

        if ($cart){
            $em->remove($cart);
            $em->flush();
        }

        $cartItems   = $em->getRepository(Cart::class)->getCartByUser($this->getUser()->getId());

        $result = [
            'alert_message' => '',
            'cart_message'  => count($cartItems) .' '. $translator->transChoice('some.translation.key', count($cartItems) ).' '. $translator->trans('in cart'),
            'error_message' => '',
        ];

        return new JsonResponse($result);
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout(Request $request)
    {

        $user  = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Cart::class);
        $cart  = $repository->getCartByUser($user->getId());
        $cart  = $repository->find($cart[0]['id']);
        $order = new Order();
        $data  = [];

        $fullCart = $repository->getFullCartByUser($user->getId());
        $total = 0;

        foreach ($fullCart as $item):
            $data[] = [
                'title'  => $item['title'],
                'price'  => $item['price'],
                'amount' => $item['amount'],
            ];
            $total = $total + $item['price'] * $item['amount'];
        endforeach;

        $data['total'] = $total;
        $order
            ->setUser($user)
            ->setData($data)
        ;


        $em->persist($order);
        $em->remove($cart);
        $em->flush();

        return $this->render('cart/checkout.html.twig', [
            'order' => $order,
        ]);
    }
}
