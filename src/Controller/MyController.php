<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\AddressType;
use App\Form\OrderPaymentType;
use App\Repository\MessageRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/my", name="my_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class MyController extends Controller
{

    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('my/index.html.twig', [
        ]);
    }


    /**
     * @Route("/orders", name="orders")
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
     * @Route("/order/{id}", name="order_show")
     */
    public function order_show(Order $order): Response
    {
        if ($order->getUserId() !== $this->getUser()->getId())
            $this->createNotFoundException();


        return $this->render('my/order/show.html.twig', [
            'order'    => $order,
        ]);
    }

        /**
         * @Route("/order/pay/{id}", name="order_pay")
         */
    public function order_pay(Order $order, Request $request): Response
    {
        if ($order->getUserId() !== $this->getUser()->getId())
            $this->createNotFoundException();

        $form    = $this->createForm(OrderPaymentType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $order->setStatusId(2);

            $em = $this->getDoctrine()->getManager();
            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('my_orders');
        }

        return $this->render('my/order/pay.html.twig', [
            'order' => $order,
            'form'  => $form,
        ]);
    }

    /**
     * @Route("/address/new", name="address_new", methods="GET|POST")
     */
    public function addressnew(Request $request): Response
    {
        $address = new Address();
        $form    = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $address->setUser($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($address);
            $em->flush();

            return $this->redirectToRoute('my_account');
        }

        return $this->render('my/address/new.html.twig', [
            'address' => $address,
            'form'    => $form->createView(),
        ]);
    }

    /**
     * @Route("/address/{id}/edit", name="address_edit", methods="GET|POST")
     */
    public function addressedit(Request $request, Address $address): Response
    {
        if ($address->getUserId() !== $this->getUser()->getId())
            $this->createNotFoundException();

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('my_account');
        }

        return $this->render('my/address/edit.html.twig', [
            'address' => $address,
            'form'    => $form->createView(),
        ]);
    }


    /**
     * @Route("/messages", name="messages", methods="GET")
     */
    public function messages(MessageRepository $repository): Response
    {
        $messages = $repository->getMessagesByUser($this->getUser());
        return $this->render('my/messages.html.twig', [
            'messages' => $messages,
        ]);
    }


    /**
     * @Route("/account", name="account")
     */
    public function personal(AddressRepository $addressRepository): Response
    {
        return $this->render('my/personal.html.twig', [
            'addresses' => $addressRepository->getUserAddress($this->getUser()->getId()),
        ]);
    }


    public function header(): Response
    {
        return $this->render('my/header.html.twig', [
        ]);
    }


    public function footer(): Response
    {
        return $this->render('my/footer.html.twig', [
        ]);

    }

    public function right($active = 'index'): Response
    {
        return $this->render('my/right.html.twig', [
            'active' => $active,
        ]);
    }
}
