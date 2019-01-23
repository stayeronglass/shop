<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Cart;
use App\Entity\Delivery;
use App\Entity\Order;
use App\Entity\Payment;
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

        $user   = $this->getUser();
        $userId = $user->getId();
        $em     = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Cart::class);

        $data  = [];
        $data['products'] = [];
        $total = 0;

        foreach ($repository->getFullCartByUser($userId) as $item):
            $data['products'][] = [
                'title'  => $item['title'],
                'price'  => $item['price'],
                'amount' => $item['amount'],
                'total'  => $item['price'] * $item['amount'],
            ];
            $total = $total + $item['price'] * $item['amount'];
        endforeach;

        if (empty($data)) {
            $this->createNotFoundException();
        }

        $data['total'] = $total;

        $address  = $em->getRepository(Address::class)->find($session->get('order_address'));
        if($address->getUserId() !== $userId) $this->createNotFoundException();

        $delivery = $em->getRepository(Delivery::class)->find($session->get('order_delivery'));
        $payment  = $em->getRepository(Payment::class)->find($session->get('order_payment'));


        $data['address']['zip']       = $address->getZip();
        $data['address']['address']   = $address->getAddress();
        $data['address']['recipient'] = $address->getRecipient();
        $data['address']['phone']     = $address->getPhone();

        $data['payment'] = $payment->getTitle();

        $data['delivery']['title'] = $delivery->getTitle();
        $data['delivery']['price'] = $delivery->getPrice();


        $order = new Order();
        $order
            ->setUser($user)
            ->setData($data)
            ->setTotal($total);

        $em->persist($order);
        $em->flush();


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
            $address = $form->getData()['address'];
            $session->set('order_address', $address->getId());

            return $this->redirectToRoute('cart_delivery');
        }

        $delivery = $this->createForm(CartDeliveryType::class);
        $payment = $this->createForm(CartPaymentType::class);

        return $this->render('cart/address.html.twig', [
            'form'     => $form->createView(),
            'delivery' => $delivery->createView(),
            'payment'  => $payment->createView(),
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
            $delivery  = $form->getData()['delivery'];
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
            $payment  = $form->getData()['payment'];
            $session->set('order_payment', $payment->getId());

            return $this->redirectToRoute('cart_checkout');
        }

        return $this->render('cart/payment.html.twig', [
            'form' => $form->createView(),
        ]);
    }



}
