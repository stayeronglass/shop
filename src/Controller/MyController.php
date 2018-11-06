<?php

namespace App\Controller;

use App\Form\AddressType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
            'controller_name' => 'LkController',
        ]);
    }


    /**
     * @Route("/orders", name="orders")
     */
    public function orders(): Response
    {
        $orders = [];
        return $this->render('my/orders.html.twig', [
            'orders' => $orders,
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
     * @Route("/address/{id}/edit", name="address_edit",methods="GET|POST")
     */
    public function addressedit(Request $request, Address $address): Response
    {
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('address_index', ['id' => $address->getId()]);
        }

        return $this->render('my/address/edit.html.twig', [
            'address' => $address,
            'form'    => $form->createView(),
        ]);
    }


    /**
     * @Route("/messages", name="messages")
     */
    public function messages(): Response
    {
        $messages = [];
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
            'addresses' => $addressRepository->findByUser($this->getUser()),
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
