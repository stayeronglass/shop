<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\KeyValue;
use App\Entity\Product;
use Psr\Cache\CacheItemPoolInterface;
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
    public function show($id): Response
    {


            $em = $this->getDoctrine()->getManager();

            $params = $em->getRepository(KeyValue::class)->getItems(['product_title_postfix', 'product_description_postfix']);

            $params['product'] = $em->getRepository(Product::class)->find($id);
            $params['images']  = $em->getRepository(Image::class)->getTImages($id);

            $data = $this->renderView('product/full.html.twig', $params);

        return new Response($data);
    }
}
