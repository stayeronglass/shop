<?php

namespace App\Controller\My;

use App\Entity\Order;
use App\Form\OrderPaymentType;
use App\Repository\OrderRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/my", name="my_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class OrdersController extends AbstractController
{

    /**
     * @Route("/orders", name="orders", methods="GET")
     */
    public function orders(PaginatorInterface $paginator, Request $request, OrderRepository $repository): Response
    {

        $orders = $paginator->paginate(
            $repository->getOrdersQueryByUser($this->getUser()->getId()),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('my/orders.html.twig', [
            'orders' => $orders,
        ]);
    }


    /**
     * @Route("/order/{id}", name="order_show", methods="GET")
     * @Security("is_granted('order', order)")
     */
    public function order_show(Order $order): Response
    {
        return $this->render('my/order/show.html.twig', [
            'order'    => $order,
        ]);
    }


    /**
     * @Route("/order/pay/{id}", name="order_pay", methods="GET|POST")
     * @Security("is_granted('order', order)")
     */
    public function order_pay(Order $order, Request $request): Response
    {
        $form    = $this->createForm(OrderPaymentType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order->setStatusId(Order::ORDER_STATUS_PAID);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('my_orders');
        }

        return $this->render('my/order/pay.html.twig', [
            'order' => $order,
            'form'  => $form->createView(),
        ]);
    }

}
