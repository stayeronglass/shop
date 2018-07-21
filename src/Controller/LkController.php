<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/my", name="my")
 */
class LkController extends Controller
{
    /**
     * @Route("/", name="my_main")
     */
    public function index()
    {
        return $this->render('lk/index.html.twig', [
            'controller_name' => 'LkController',
        ]);
    }
}
