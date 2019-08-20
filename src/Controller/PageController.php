<?php

namespace App\Controller;

use App\Entity\KeyValue;
use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{

    /**
     * @Route("/page/{slug}", name="page", methods="GET"))
     */
    public function index(string $slug): Response
    {
        $em     = $this->getDoctrine()->getManager();
        $params = $em->getRepository(KeyValue::class)->getItems(['russian_name']);
        $params['page'] = $em->getRepository(Page::class)->findOneBy(['slug' => $slug]);
        if(!$params['page']) $this->createNotFoundException();

        return $this->render('page/page.html.twig', $params);
    }
}
