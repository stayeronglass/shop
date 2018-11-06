<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        
        return $this->render('default/index.html.twig', [
            'banner'     => $banner,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/{slug}", name="category", methods="GET"))
     */
    public function category($slug, Request $request): Response
    {
        $em           = $this->getDoctrine()->getManager();
        $categoryRepo = $em->getRepository(Category::class);

        $category     = $categoryRepo->findOneBy(['slug' => $slug]);
        if(null === $category) throw $this->createNotFoundException();

        $title = $category->getName();
        $catQb = $categoryRepo->getChildrenQueryBuilder($category, true, null, 'ASC', true);

        $query     = $em->getRepository(Product::class)->getProductByCategory($catQb);
        $products  = $this->get('knp_paginator')->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('per_page', 10)/* items per page*/
        );

        return $this->render('default/category.html.twig', [
            'category' => $category,
            'products' => $products,
            'title'    => $title,
        ]);
    }


    /**
     * @Route("/contacts", name="contacts", methods="GET"))
     */
    public function contacts(): Response
    {
        return $this->render('default/contacts.html.twig', [
        ]);
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
        return $this->render('default/footer.html.twig', [
        ]);
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
     * @Route("/termsandconditions", name="termsandconditions", methods="GET"))
     */
    public function termsandconditions(): Response
    {
        return $this->render('default/termsandconditions.html.twig', [
        ]);
    }
}
