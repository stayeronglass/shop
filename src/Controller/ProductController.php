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
     */
    public function show(Product $product, KeyValueRepository $kv, ImageRepository $imageRepository): Response
    {
        $params = $kv->getItems(['product_title_postfix', 'product_description_postfix']);
        $params['product'] = $product;
        $params['images']  = $imageRepository->getTImages($product->getId());

        return $this->render('product/full.html.twig', $params);
    }
}
