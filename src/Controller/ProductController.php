<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Product;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/product")
 */

class ProductController extends Controller
{


    /**
     * @Route("/{id}", name="product_show", methods="GET"))
     */
    public function show($id): Response
    {
        $em      = $this->getDoctrine()->getManager();
        $product = $em->getRepository(Product::class)->find($id);
        if(null === $product) throw $this->createNotFoundException();

        $images  = $em->getRepository(Image::class)->getTImages($product->getId());

        return $this->render('product/full.html.twig', [
            'product' => $product,
            'images'  => $images,
        ]);
    }
}
