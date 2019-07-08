<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ImageRepository;
use App\Repository\KeyValueRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/product")
 */

class ProductController extends Controller
{


    /**
     * @Route("/{id}", name="product_show", methods="GET"))
     * @Cache(lastModified="product.getUpdatedAt()", Etag="'product' ~ product.getId() ~ product.getUpdatedAt().getTimestamp()")
     */
    public function show(Product $product, KeyValueRepository $kv, ImageRepository $imageRepository): Response
    {
        return $this->render('product/full.html.twig', [
            'product' => $product,
            'images'  => $imageRepository->getTImages($product->getId()),
            'title_postfix' => $kv->findOneBy(['key' => 'p_title_postfix']),
        ]);
    }
}
