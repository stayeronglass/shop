<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\My\Address;
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
use Symfony\Contracts\Translation\TranslatorInterface;


/**
 * @Route("/cart", name="cart_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class CartController extends AbstractController
{


    /**
     * @Route("/", name="index", methods="GET|POST"))
     */
    public function index(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Cart::class);

        $userId = $this->getUser()->getId();

        if ($request->isMethod('post')):
            $this->recalc($request->request->all(), $em, $repository, $userId);
        endif;

        $cart = $repository->getFullCartByUser($this->getUser()->getId());

        return $this->render('cart/index.html.twig', [
            'cart'  => $cart,
        ]);
    }

    private function recalc($data, $em, $repository, $userId)
    {
        foreach ($data  as $id => $value):
            $amount = (int) $value;
            $cart = $repository->findOneBy(['id' => $id, 'user_id' => $userId]);

            if(!$cart) continue;

            if ($amount === 0):
                $em->remove($cart);
            elseif ($cart->getAmount() !== $amount):
                $cart->setAmount($amount);
                $em->persist($cart);
            endif;
        endforeach;

        $em->flush();
    }

    /**
     * @Route("/add", name="add")
     */
    public function add(Request $request, CartRepository $cartRepository, TranslatorInterface $translator): JsonResponse
    {
        $em             = $this->getDoctrine()->getManager();
        $product        = $em->getRepository(Product::class)->find($request->get('product_id'));
        $user           = $this->getUser();
        $error_message  = '';

        if ($product){
            $cartRepository->add($request->query->getInt('amount', 1), $product, $user);
        } else {
            $error_message = $translator->trans('product not found');
        }

        $result = [
            'message' => $translator->trans('item added to cart'),
            'cart'    => $this->renderView('default/_cart.html.twig', [
                'cart_items' => $cartRepository->getCartAmountByUser($user->getId()),
            ]),
          'error_message'    => $error_message,
        ];

        return new JsonResponse($result);
    }


    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(int $id): JsonResponse
    {
        $em       = $this->getDoctrine()->getManager();
        $cartRepo = $em->getRepository(Cart::class);
        $user_id  = $this->getUser()->getId();
        $cart     = $cartRepo->findOneBy(['id' => $id, 'user_id' => $user_id]);

        if ($cart) {
            $em->remove($cart);
            $em->flush();
        }

        $cartItems = $cartRepo->getCartAmountByUser($user_id);

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
        $addressMessage = '';
        $form->handleRequest($request);
        $formData = $form->getData();
        $address  = $formData['address'];

        if ($form->isSubmitted() && $form->isValid() && $address){

            $order = $this->createOrder($form);
            $session->set('order', $order->getId());

            return $this->redirectToRoute('cart_finish');
        }

        if(!$address) $addressMessage = 'Добавьте хотябы один адрес!';

        return $this->render('cart/checkout.html.twig', [
            'form'     => $form->createView(),
            'addressMessage' => $addressMessage,
        ]);
    }


    /**
     * @Route("/finish", name="finish", methods="GET")
     */
     public function finish(OrderRepository $repository, SessionInterface $session): Response
     {
         $order = $repository->find($session->get('order'));
         if (!$order) throw $this->createNotFoundException();


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
            throw $this->createNotFoundException();
        }



        $data['total'] = $total;

        $formData = $form->getData();

        $address  = $formData['address'];

        if($address->getUserId() !== $userId) throw $this->createNotFoundException();

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
