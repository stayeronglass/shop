<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\KeyValue;
use App\Entity\Product;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

/**
 * @Route("/product")
 */

class ProductController extends Controller
{
    /**
     * @Route("/{id}", name="product_show", methods="GET"))
     */
    public function show($id, Request $request, TagAwareCacheInterface $cache): Response
    {
        $user = (bool) $this->getUser();
        $response = new Response();
        $now = new \DateTime();
        $etag = md5($now->getTimestamp());

        if (!$user) {
            $product_etag = $cache->getItem('product_' . $id . '_etag');
            $response->setEtag($product_etag->get());
            // if ($response->isNotModified($request)) return $response;

            $product = $cache->getItem('product_' . $id);
            $item = $product->get();
            //  if ($item) return $response->setContent($item)->setEtag($etag);
        }
        $em = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Product::class);

        $params['product'] = $repo->find($id);
        if (!$params['product']) throw new NotFoundHttpException();

        $params = array_merge($params, $em->getRepository(KeyValue::class)->getItems(['product_title_postfix', 'product_description_postfix']));
        $params['images'] = $repo->getTImages($id);
        $data = $this->renderView('product/full.html.twig', $params);

        if (!$user) {
            $product->set($data);
            $cache->save($product);

            $product_etag->set($etag);
            $cache->save($product_etag);
        }

        $response
            ->setEtag($etag)
            ->setLastModified($now)
            ->setContent($data)
        ;

        return $response;
    }
}
