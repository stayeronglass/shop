<?php

namespace App\Controller\My;

use App\Entity\Order;
use App\Form\OrderPaymentType;
use App\Repository\OrderRepository;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Event\Subscriber\Paginate\Doctrine\ORM\QuerySubscriber\UsesPaginator;
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

        $query = $repository->getOrdersQueryByUser($this->getUser()->getId());

        $query
            ->setHint(UsesPaginator::HINT_FETCH_JOIN_COLLECTION, false)
            ->setHydrationMode(Query::HYDRATE_SCALAR)
        ;

        $orders = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('my/orders.html.twig', [
            'orders' => $orders,
        ]);
    }


    /**
     * @Route("/order/{id}", name="order_show", methods="GET")
     */
    public function show(Order $order): Response
    {
        if ($order->getUserId() !== $this->getUser()->getId()) throw $this->createNotFoundException();

        return $this->render('my/order/show.html.twig', [
            'order'    => $order,
        ]);
    }


    /**
     * @Route("/order/pay/{id}", name="order_pay", methods="GET|POST")
     */
    public function pay(Order $order, Request $request): Response
    {
        if ($order->getUserId() !== $this->getUser()->getId()) throw $this->createNotFoundException();

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

    /**
     * @Route("/order/payment", name="payment", methods="GET")
     */
    public function payment()
    {

    }
}
