<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PageController extends AbstractController
{

    /**
     * @Route("/page/{slug}", name="page", methods="GET"))
     */
    public function index(Page $page): Response
    {
        return $this->render('page/page.html.twig', [
            'page' => $page,
        ]);
    }
}
