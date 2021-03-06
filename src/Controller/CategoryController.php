<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\KeyValue;
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
        $em           = $this->getDoctrine()->getManager();
        $categoryRepo = $em->getRepository(Category::class);
        $kv           = $em->getRepository(KeyValue::class);

        return $this->render('category/main.html.twig', [
            'categories'    => $categoryRepo->getMainCategories(),
            'title_postfix' => $kv->getValue('main_cat_title_postfix'),
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
        if((null === $category) || $category->getSpecial()) throw $this->createNotFoundException();

        $kv    = $em->getRepository(KeyValue::class);
        $catQb = $categoryRepo->getChildrenQueryBuilder($category, true, 'id', 'ASC', true);

        $query = $em->getRepository(Product::class)->getProductByCategory($catQb);
        $query
            ->setHint(UsesPaginator::HINT_FETCH_JOIN_COLLECTION, false)
            ->setHydrationMode(Query::HYDRATE_SCALAR)
        ;

        $page = $request->query->getInt('page', 1);
        $products  = $paginator->paginate(
            $query, /* query NOT result */
            $page,
            $request->query->getInt('per_page', 20)/* items per page*/

        );

        return $this->render('category/category.html.twig', [
            'category'   => $category,
            'products'   => $products,
            'categories' => $categoryRepo->getCategories($catQb),
            'page'       => $page,
            'title_postfix' => $kv->getValue('cat_title_postfix'),
        ]);
    }


}
