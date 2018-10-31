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
        $em        = $this->getDoctrine()->getManager();
        $product   = $em->getRepository(Product::class)->find($request->get('product_id'));
        $user      = $this->getUser();
        $cartRepo  = $em->getRepository(Cart::class);
        $cartItems = $cartRepo->getFullCartByUser($user->getId());


        $found = false;
        foreach ($cartItems as $item) {
            if($item['pid'] == $product->getId()){
                $found = true;
                $cart  = $cartRepo->find($item['id']);
                $cart->setAmount($cart->getAmount() + 1);
                break;
            }
        }

        if(!$found){
            $cart = new Cart();
            $cart
                ->setUser($user)
                ->setProduct($product)
                ->setAmount($request->query->getInt('amount', 1))
            ;
        }

        $em->persist($cart);
        $em->flush();

        $cartItems = $cartRepo->getCartAmountByUser($user->getId());

        $result = [
          'alert_message' => 'Товар добавлен в корзину',
          'full_cart_message'  => '<span id="cart_items_count">'.($cartItems).'</span>' . $translator->transChoice('some.translation.key', ($cartItems) ) .' ' . $translator->trans('in cart'),
          'short_cart_message'  => '<span id="cart_items_count">'.($cartItems).'</span>' . $translator->transChoice('some.translation.key', ($cartItems) ).' '.'<span class="hidden-md">'. $translator->trans('in cart') . '</span>',
          'error_message' => '',
        ];

        return new JsonResponse($result);
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove($id, TranslatorInterface $translator)
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
            'alert_message' => '',
            'cart_message'  => $cartItems .' '. $translator->transChoice('some.translation.key', $cartItems).' '. $translator->trans('in cart'),
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
}
