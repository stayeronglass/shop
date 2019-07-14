<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\KeyValue;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use App\Entity\Cart;
use Symfony\Component\HttpFoundation\Response;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="main", methods="GET")
     */
    public function index(): Response
    {
        $em         = $this->getDoctrine()->getManager();
        $banner     = $em->getRepository(Product::class)->getSliderProducts();
        $categories = $em->getRepository(Category::class)->getMainCategories();
        $kvr        = $em->getRepository(KeyValue::class);

        return $this->render('default/index.html.twig', [
            'banner'     => $banner,
            'categories' => $categories,
            'title'      => $kvr->getItems(['site_name'])['site_name'],
        ]);
    }



    public function inner(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $params = $em->getRepository(KeyValue::class)->getItems(['russian_name', 'main_page_text']);

        $params['categories'] = $em->getRepository(Category::class)->getMainCategories();
        $params['banner']     = $em->getRepository(Product::class)->getSliderProducts();

        return $this->render('default/main_inner.html.twig', $params);
    }

    /**
     * @Route("/contacts", name="contacts", methods="GET"))
     */
    public function contacts(): Response
    {
        return $this->render('default/contacts.html.twig', [
        ]);
    }


    public function head(): Response
    {
        $em         = $this->getDoctrine()->getManager();
        $kvr        = $em->getRepository(KeyValue::class);

        return $this->render('_layout/head.html.twig', $kvr->getItems([
            'yandex_metrica', 'description', 'keywords'
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

        $params = $kvr->getItems(['vk', 'russian_name ']);
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
     * @Route("/about", name="about", methods="GET"))
     */
    public function about(): Response
    {
        return $this->render('default/about.html.twig', [
        ]);
    }


    /**
     * @Route("/faq", name="faq", methods="GET"))
     */
    public function faq(): Response
    {
        return $this->render('default/faq.html.twig', [
            'title' => 'FAQ',
        ]);
    }


    /**
     * @Route("/privacy", name="privacy", methods="GET"))
     */
    public function privacy(): Response
    {
        return $this->render('default/privacy.html.twig', [
            'title' => 'FAQ',
        ]);
    }




    /**
     * @Route("/termsandconditions", name="termsandconditions", methods="GET"))
     */
    public function termsandconditions(): Response
    {
        return $this->render('default/termsandconditions.html.twig', [
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
