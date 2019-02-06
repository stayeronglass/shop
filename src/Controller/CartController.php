<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\OrderStatus;
use App\Entity\Product;
use App\Repository\CartRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use App\Form\CartCheckoutType;

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
    public function checkout(Request $request, SessionInterface $session): Response
    {
        $form = $this->createForm(CartCheckoutType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $order = $this->createOrder($form);
            $session->set('order', $order->getId());

            return $this->redirectToRoute('cart_finish');
        }


        return $this->render('cart/checkout.html.twig', [
            'form'     => $form->createView(),
        ]);
    }

    /**
     * @Route("/finish", name="finish", methods="GET")
     */

     public function finish(OrderRepository $repository, SessionInterface $session): Response
     {
         $order = $repository->find($session->get('order'));
         if (!$order) $this->createNotFoundException();


         return $this->render('cart/finish.html.twig', [
             'order'     => $order,
         ]);
     }

    public function createOrder(Form $form): Order
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

        $formData = $form->getData();
        $address  = $formData['address'];

        if($address->getUserId() !== $userId) $this->createNotFoundException();

        $delivery = $formData['delivery'];
        $payment  = $formData['payment'];


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
            ->setTotal($total)
            ->setStatusId(OrderStatus::ORDER_STATUS_CREATED);
        ;

        $em->persist($order);
        $em->flush();

        $repository->clearCartByUser($userId);

       return $order;
    }

}
