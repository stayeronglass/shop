<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\AddressType;
use App\Form\CartAddressType;
use App\Form\CartDeliveryType;
use App\Repository\CartRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CartPaymentType;

/**
 * @Route("/cart", name="cart_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class CartController extends AbstractController
{


    /**
     * @Route("/", name="index", methods="GET|POST"))
     */
    public function index(CartRepository $repository): Response
    {
        $cart = $repository->getFullCartByUser($this->getUser()->getId());

        return $this->render('cart/index.html.twig', [
            'cart'  => $cart,
        ]);
    }


    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, CartRepository $cartRepository): JsonResponse
    {
        $em        = $this->getDoctrine()->getManager();


        $product   = $em->getRepository(Product::class)->find($request->get('product_id'));
        $user      = $this->getUser();
        $error_message = '';

        if ($product){
            $cartRepository->add($request->query->getInt('amount', 1), $product, $user);
        } else {
            $error_message = 'product mot found!';
        }

        $result = [
          'message' => 'Товар добавлен в корзину',
            'cart'  => $this->renderView('default/_cart.html.twig', [
                'cart_items' => $cartRepository->getCartAmountByUser($user->getId()),
            ]),
          'error_message' => $error_message,
        ];

        return new JsonResponse($result);
    }


    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id): JsonResponse
    {
        $em       = $this->getDoctrine()->getManager();
        $cartRepo = $em->getRepository(Cart::class);
        $user     = $this->getUser();
        $cart     = $cartRepo->findOneBy(['id' => $id, 'user_id' => $user->getId()]);

        if ($cart){
            $em->remove($cart);
            $em->flush();
        }

        $cartItems = $cartRepo->getCartAmountByUser($user->getId());

        $result = [
            'message' => '',
            'cart'  => $this->renderView('default/_cart.html.twig', [
                'cart_items' => ($cartItems),
            ]),
            'error_message' => '',
        ];

        return new JsonResponse($result);
    }


    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout(SessionInterface $session): Response
    {

        $user    = $this->getUser();
        $userId  = $user->getId();
        $em      = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Cart::class);

        $data  = [];
        $total = 0;

        foreach ($repository->getFullCartByUser($userId) as $item):
            $data[] = [
                'title'  => $item['title'],
                'price'  => $item['price'],
                'amount' => $item['amount'],
            ];
            $total = $total + $item['price'] * $item['amount'];
        endforeach;

        if (!empty($data)){
            $data['address']  = $session->get('order_address');
            $data['delivery'] = $session->get('order_delivery');
            $data['paymenn']  = $session->get('order_delivery');

            $order = new Order();
            $data['total'] = $total;
            $order
                ->setUser($user)
                ->setData($data)
            ;

            $em->persist($order);
            $em->flush();
            $repository->clearCartByUser($userId);
        }

        return $this->render('cart/checkout.html.twig', [
            'order' => $order,
        ]);
    }


    /**
     * @Route("/address", name="address")
     */
    public function address(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(CartAddressType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $address = $form->getData();
            $session->set('order_address', $address->getId());

            return $this->redirectToRoute('cart_delivery');
        }

        return $this->render('cart/address.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/delivery", name="delivery")
     */
    public function delivery(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(CartDeliveryType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $delivery  = $form->getData();
            $session->set('order_delivery', $delivery->getId());

            return $this->redirectToRoute('cart_payment');
        }

        return $this->render('cart/delivery.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/payment", name="payment")
     */
    public function payment(Request $request, SessionInterface $session)
    {
        $form = $this->createForm(CartPaymentType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $payment  = $form->getData();
            $session->set('order_payment', $payment->getId());

            return $this->redirectToRoute('cart_checkout');
        }

        return $this->render('cart/payment.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
