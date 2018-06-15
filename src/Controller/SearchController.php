<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SearchController extends Controller
{
    /**
     * @Route("/search", name="search")
     */
    public function search()
    {
        return $this->render('search/full.html.twig', [
            'controller_name' => 'LkController',
        ]);
    }
}
