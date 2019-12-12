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
        $user     = (bool) $this->getUser();
        $response = new Response();
        $now      = new \DateTime();
        $etag     = md5($now->getTimestamp());

        if (!$user) {
            $index_etag = $cache->getItem('index_etag');
            $etag = $index_etag->get() ?? $etag;
            $response->setEtag($etag, true);
            if ($response->isNotModified($request)) return $response;

            $index = $cache->getItem('index');
            $item = $index->get();
            if ($item) return $response->setContent($item)->setEtag($etag);
        }

        $em   = $this->getDoctrine()->getManager();
        $kvr  = $em->getRepository(KeyValue::class);
        $data = $this->renderView('default/index.html.twig', $kvr->getItems(['main_html_title', 'html_description', 'html_keywords']));

        if (!$user) {
            $index->set($data)->expiresAfter(3600);
            $cache->save($index);

            $index_etag->set($etag)->expiresAfter(3600);
            $cache->save($index_etag);
        }

        $response
            ->setEtag($etag, true)
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
        $params = $repository->getItems(['yandex_metrica']);
        $params['canonical'] = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if(!empty($_REQUEST['page'])) $params['noindex'] = 1;

        return $this->render('_layout/head.html.twig', $params);
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

        $params = $kvr->getItems(['vk', 'russian_name', 'vk_community_messages']);
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
