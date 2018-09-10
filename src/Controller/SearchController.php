<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * @Route("/search", name="search_")
 */

class SearchController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function search()
    {
        return $this->render('search/full.html.twig', [
            'controller_name' => 'LkController',
        ]);
    }
}
