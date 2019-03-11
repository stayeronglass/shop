<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/search", name="search_")
 */

class SearchController extends Controller
{


    /**
     * @Route("/", name="index", methods="GET")
     */
    public function search(Request $request, ProductRepository $repository, PaginatorInterface $paginator): Response
    {
        $query = $repository->searchProductsQuery($request->get('q'));

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12
        );

        return $this->render('search/full.html.twig', [
            'products' => $pagination,
        ]);
    }
}
