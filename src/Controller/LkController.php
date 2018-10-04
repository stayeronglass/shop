<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/my", name="my_")
 * @IsGranted("IS_AUTHENTICATED_FULLY")
 */
class LkController extends Controller
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        return $this->render('lk/index.html.twig', [
            'controller_name' => 'LkController',
        ]);
    }

    public function header(){
        return $this->render('lk/header.html.twig', [
        ]);
    }

    public function footer(){
        return $this->render('lk/footer.html.twig', [
        ]);

    }
}
