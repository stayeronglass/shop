<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
        ]);
    }

    public function header(){
        return $this->render('default/header.html.twig', [
        ]);
    }

    public function footer(){
        return $this->render('default/footer.html.twig', [
        ]);
    }
}
