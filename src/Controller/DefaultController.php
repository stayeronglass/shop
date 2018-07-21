<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
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
     * @Route("/category/{category}", name="category", requirements={"category"="\s+"})
     */
    public function category(Category $category)
    {

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
        return $this->render('default/header.html.twig', [
        ]);
    }

    public function footer(){
        return $this->render('default/footer.html.twig', [
        ]);
    }
}
