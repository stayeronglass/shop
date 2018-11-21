<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Form\DeliveryType;
use App\Form\MasterCartType;
use App\Repository\CartRepository;
use PhpParser\Node\Expr\New_;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/cart", name="cart_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class CartController extends Controller
{
    /**
     * @Route("/", name="index", methods="GET|POST"))
     */
    public function index(Request $request, CartRepository $repository): Response
    {
        $cart = $repository->getFullCartByUser($this->getUser()->getId());
        $em        = $this->getDoctrine()->getManager();

        $items = $em->getRepository(Cart::class)->findBy(['user_id' => $this->getUser()->getId()]);
        $form = $this->createForm(MasterCartType::class, $items);

        return $this->render('cart/index.html.twig', [
            'cart'  => $cart,
            'form'  => $form->createView(),
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
    public function remove($id, TranslatorInterface $translator): JsonResponse
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
    public function checkout(Request $request): Response
    {

        $user  = $this->getUser();
        $em    = $this->getDoctrine()->getManager();
        $repository = $em->getRepository(Cart::class);

        $data  = [];
        $total = 0;

        foreach ($repository->getFullCartByUser($user->getId()) as $item):
            $data[] = [
                'title'  => $item['title'],
                'price'  => $item['price'],
                'amount' => $item['amount'],
            ];
            $total = $total + $item['price'] * $item['amount'];
        endforeach;

        if (!empty($data)){
            $order = new Order();
            $data['total'] = $total;
            $order
                ->setUser($user)
                ->setData($data)
            ;

            $em->persist($order);
            $em->flush();
            $repository->clearCartByUser($user->getId());
        }

        return $this->render('cart/checkout.html.twig', [
            'order' => $order,
        ]);
    }

    /**
     * @Route("/delivery", name="delivery")
     */
    public function delivery(Request $request)
    {
        $form = $this->createForm(DeliveryType::class);

        return $this->render('cart/delivery.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
