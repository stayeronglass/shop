<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\KeyValue;
use App\Entity\Product;
use App\Repository\KeyValueRepository;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use App\Entity\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ADefaultController extends Controller
{
    /**
     * @Route("/", name="main", methods="GET")
     */
    public function index(Request $request, TagAwareCacheInterface $cache): Response
    {
        $response = new Response();
        $now  = new \DateTime();
        $etag = md5($now->getTimestamp());

//        if (!$this->getUser()) {
//            $item = $cache->getItem('index_etag');
//            $response->setEtag($item->get());
//            if ($response->isNotModified($request)) return $response->setPublic();
//
//            $now = new \DateTime();
//            $etag = md5($now->getTimestamp());
//            $item->set($etag)->tag(['index']);
//            $cache->save($item);
//            $response
//                ->setEtag($etag)
//                ->setLastModified($now);
//        }

        $em   = $this->getDoctrine()->getManager();
        $kvr  = $em->getRepository(KeyValue::class);
        $data = $this->renderView('default/index.html.twig', $kvr->getItems(['main_html_title', 'html_description', 'html_keywords']));


        $item = $cache->getItem('index_etag');
        $item->set($etag)->tag(['index']);
        $cache->save($item);

        $response
            ->setPublic()
            ->setEtag($etag)
            ->setLastModified($now)
            ->setContent($data);

        return $response;
    }




    public function inner(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $params = $em->getRepository(KeyValue::class)->getItems(['russian_name', 'main_page_text']);

        $params['categories'] = $em->getRepository(Category::class)->getMainCategories();
        $params['banner']     = $em->getRepository(Product::class)->getSliderProducts();

        return $this->render('default/main_inner.html.twig', $params);
    }

    public function head(KeyValueRepository $repository): Response
    {

        return $this->render('_layout/head.html.twig', $repository->getItems([
            'yandex_metrica',
        ]));
    }


    public function header(): Response
    {
        $items = 0;

        if ($this->isGranted('IS_AUTHENTICATED_FULLY')){
            $em    = $this->getDoctrine()->getManager();
            $items = $em->getRepository(Cart::class)->getCartAmountByUser($this->getUser()->getId());
        }

        return $this->render('default/header.html.twig', [
            'q'            => $_GET['q'] ?? '',
            'redirect_url' => $_SERVER['REQUEST_URI'],
            'cart_items'   => $items,
        ]);
    }

    public function footer(): Response
    {

        $em         = $this->getDoctrine()->getManager();
        $kvr        = $em->getRepository(KeyValue::class);

        $params = $kvr->getItems(['vk', 'russian_name']);
        $params['categories'] = $em->getRepository(Category::class)->getMainCategories();
        return $this->render('default/footer.html.twig',
            $params
        );
    }

    /**
     * @Route("/autocomplete", name="autocomplete", methods="POST"))
     */
    public function autocomplete(): JsonResponse
    {
        $data = [];

        return new JsonResponse($data);
    }


    /**
     * @Route("/breadcrumbs", name="breadcrumbs")
     */
    public function breadcrumbs($node, $product = null): Response
    {
        $em   = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Category::class);

        if (is_scalar($node)){
            $node = $repo->find($node);
        }

        if (null === $node) throw $this->createNotFoundException();

        return $this->render('default/breadcrumbs.html.twig', [
            'breadcrumbs' => $repo->getPathQuery($node)->getScalarResult(),
            'product'     => $product,
        ]);
    }


    /**
     * @Route("/info", name="info", methods="GET"))
     * condition="%kernel.environment% === 'dev'"
     */
    public function info()
    {
        phpinfo();
        exit;
    }

}
