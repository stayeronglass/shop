<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\Query;
use Knp\Component\Pager\Event\Subscriber\Paginate\Doctrine\ORM\QuerySubscriber\UsesPaginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends Controller
{


    /**
     * @Route("/categories/", name="main_categories", methods="GET"))
     */
    public function maincategories() : Response
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepo = $em->getRepository(Category::class);

        return $this->render('category/main.html.twig', [
            'categories' => $categoryRepo->getMainCategories(),
            'category'   => ['name' => 'Категории'],
        ]);
    }


    /**
     * @Route("/categories/{slug}", name="categories", methods="GET"))
     */
    public function categories(string $slug): Response
    {
        $em = $this->getDoctrine()->getManager();
        $categoryRepo = $em->getRepository(Category::class);


        $category = $categoryRepo->findOneBy(['slug' => $slug]);
        if (null === $category) throw $this->createNotFoundException();

        $categories = $categoryRepo
            ->getChildrenQueryBuilder($category, true, null, 'ASC', false)
            ->getQuery()
            ->getScalarResult();

        return $this->render('category/categories.html.twig', [
            'categories' => $categories,
            'category'   => $category,
        ]);
    }


    /**
     * @Route("/category/{slug}", name="category", methods="GET"))
     */
    public function category(string $slug, Request $request, PaginatorInterface $paginator): Response
    {
        $em           = $this->getDoctrine()->getManager();
        $categoryRepo = $em->getRepository(Category::class);

        $category     = $categoryRepo->findOneBy(['slug' => $slug]);
        if(null === $category) throw $this->createNotFoundException();

        $catQb = $categoryRepo->getChildrenQueryBuilder($category, true, null, 'ASC', true);

        $query = $em->getRepository(Product::class)->getProductByCategory($catQb);
        $query
            ->setHint(UsesPaginator::HINT_FETCH_JOIN_COLLECTION, false)
            ->setHydrationMode(Query::HYDRATE_SCALAR)
        ;


        $products  = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            $request->query->getInt('per_page', 10)/* items per page*/

        );

        return $this->render('category//category.html.twig', [
            'category' => $category,
            'products' => $products,
        ]);
    }


}