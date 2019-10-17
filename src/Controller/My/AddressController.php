<?php

namespace App\Controller\My;

use App\Entity\My\Address;
use App\Form\My\AddressType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/my", name="my_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class AddressController extends AbstractController
{
    /**
     * @Route("/address/{id}", name="address_show")
     */
    public function address_show(Address $address): Response
    {
        if ($address->getUserId() !== $this->getUser()->getId()) throw $this->createNotFoundException();

        return $this->render('my/order/show.html.twig', [
            'address'    => $address,
        ]);
    }


    /**
     * @Route("/addressnew", name="address_new", methods="GET|POST")
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
        if ($address->getUserId() !== $this->getUser()->getId()) throw $this->createNotFoundException();

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



}
