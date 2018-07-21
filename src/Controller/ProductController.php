<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
/**
 * @Route("/product")
 */

class ProductController extends Controller
{
    /**
     * @Route("/{id}", name="product_show")
     */
    public function show(Product $product)
    {
        $em = $this->getDoctrine()->getManager();

        $images = $em->getRepository(Image::class)->getTImages($product->getId());
        return $this->render('product/full.html.twig', [
            'product' => $product,
            'images'  => $images,
        ]);
    }
}
