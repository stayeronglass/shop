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
        $em = $this->getDoctrine()->getManager();
        $banner = $em->getRepository(Product::class)->getSliderProducts();
        $categories = $em->getRepository(Category::class)->getMainCategories();
        
        return $this->render('default/index.html.twig', [
            'banner'     => $banner,
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/category/{slug}", name="category")
     */
    public function category(Category $category)
    {
        return $this->render('default/category.html.twig', [
            'category' => $category,
            'csrf_token' => '',
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

    public function header(){

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
        $repo = $categories = $em->getRepository(Category::class);

        if(is_integer($node))
            $node = $repo->find($node);

        $breadcrumbs = $repo->getPathQuery($node)->getArrayResult();

        return $this->render('default/breadcrumbs.html.twig', [
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
