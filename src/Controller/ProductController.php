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
        $user     = (bool) $this->getUser();
        $response = new Response();
        $response->setPublic();

        $now      = new \DateTime();
        $etag     = md5($now->getTimestamp());


        if (!$user && ('dev' !== $_SERVER['APP_ENV'])) {
            $product_etag = $cache->getItem('product_' . $id . '_etag');
            $etag = $product_etag->get() ?? $etag;

            $response->setEtag($etag, true);
            if ($response->isNotModified($request)) return $response;

            $productItem = $cache->getItem('product_' . $id);
            $item = $productItem->get();
            if ($item) return $response->setContent($item)->setEtag($etag, true);
        }

        $em   = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Product::class);

        if (null === ($product = $repo->find($id))) throw new NotFoundHttpException();


        $params = $em->getRepository(KeyValue::class)->getItems(['product_title_postfix', 'product_description_postfix']);
        $params['product'] = $product;
        $params['images']  = $repo->getTImages($id);
        $content = $this->renderView('product/full.html.twig', $params);

        if (!$user && ('dev' !== $_SERVER['APP_ENV'])) {
            $productItem->set($content)->expiresAfter(Product::CACHE_TIMEOUT);
            $cache->save($productItem);

            $product_etag->set($etag)->expiresAfter(Product::CACHE_TIMEOUT_ETAG);
            $cache->save($product_etag);
        }

        $response
            ->setEtag($etag, true)
            ->setLastModified($now)
            ->setContent($content)
        ;

        return $response;
    }
}
