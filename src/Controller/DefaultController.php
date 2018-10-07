<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Cart;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="main")
     */
    public function index()
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
     * @Route("/category/{slug}", name="category")
     */
    public function category($slug, Request $request)
    {
        $em           = $this->getDoctrine()->getManager();
        $categoryRepo = $em->getRepository(Category::class);

        $category     = $categoryRepo->findOneBy(['slug' => $slug]);
        if(null === $category) throw $this->createNotFoundException();

        $title = $category->getName();
        $catQb = $categoryRepo->getChildrenQueryBuilder($category, true);

        $query     = $em->getRepository(Product::class)->getProductByCategory($catQb);
        $products  = $this->get('knp_paginator')->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('per_page', 10)/* items per page*/
        );

        return $this->render('default/category.html.twig', [
            'category' => $category,
            'products' => $products,
            'title' => $title,
        ]);
    }


    /**
     * @Route("/contacts", name="contacts")
     */
    public function contacts()
    {
        return $this->render('default/contacts.html.twig', [
        ]);
    }

    public function header()
    {
        $items = 0;
        if ($this->isGranted('IS_AUTHENTICATED_FULLY')){
            $em    = $this->getDoctrine()->getManager();
            $cart  = $em->getRepository(Cart::class)->getCartByUser($this->getUser()->getId());
            $items = count($cart);
        }

        return $this->render('default/header.html.twig', [
            'q'            => $_GET['q'] ?? '',
            'redirect_url' => $_SERVER['REQUEST_URI'],
            'cart_items'   => $items,
        ]);
    }

    public function footer(){
        return $this->render('default/footer.html.twig', [
        ]);
    }

    /**
     * @Route("/breadcrumbs", name="breadcrumbs")
     */
    public function breadcrumbs($node)
    {
        $em   = $this->getDoctrine()->getManager();
        $repo = $em->getRepository(Category::class);

        if(is_int($node))
            $node = $repo->find($node);

        return $this->render('default/breadcrumbs.html.twig', [
            'breadcrumbs' => $repo->getPathQuery($node)->getArrayResult(),
        ]);
    }
}
