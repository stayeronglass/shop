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

        $products = $em->getRepository(Product::class)->searchProducts($request->get('q'));

        var_dump($products);
    exit;
        return $this->render('search/full.html.twig', [
            'products' => $products,
        ]);
    }
}
