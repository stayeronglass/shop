<?php

namespace App\Controller;

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
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/my", name="my_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class MyController extends Controller
{

    /**
     * @Route("/", name="main", methods="GET")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('my_orders');

        return $this->render('my/index.html.twig', [
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
     * @Route("/account", name="account", methods="GET")
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
