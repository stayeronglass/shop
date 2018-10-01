<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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

    public function header(RequestStack $requestStack){
        $request  = $requestStack->getMasterRequest();
        $q        = $request->get('q', '');
        $redirect = $request->getRequestUri();

        return $this->render('default/header.html.twig', [
            'q'            => $q,
            'redirect_url' => $redirect,
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
