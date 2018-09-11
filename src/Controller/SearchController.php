<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
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
    public function search(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository(Product::class)->searchProductsQuery($request->get('q'));

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/
        );

        return $this->render('search/full.html.twig', [
            'products' => $pagination,
        ]);
    }
}
