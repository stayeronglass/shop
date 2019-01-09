<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\AddressType;
use App\Repository\MessageRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function orders(OrderRepository $repository): Response
    {
        $orders = $repository->getOrdersByUser($this->getUser());

        return $this->render('my/orders.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/order/{id}", name="order_show")
     */
    public function order_show(Order $order): Response
    {
        return $this->render('my/orders.html.twig', [
            'order' => $order,
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
            'addresses' => [],
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
