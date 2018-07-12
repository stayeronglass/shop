<?php

namespace App\Controller;

use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductController extends Controller
{
    /**
     * @Route("/product/{id}", name="product_show")
     */
    public function show(Product $product)
    {

        return $this->render('product/full.html.twig', [
            'product' => $product,
        ]);
    }
}
