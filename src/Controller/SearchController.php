<?php

namespace App\Controller;

use App\Entity\KeyValue;
use App\Repository\SearchRepository;
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
    public function search(Request $request, SearchRepository $repository, PaginatorInterface $paginator): Response
    {

        $search = trim($request->get('q'));
        $query  = [];
        if ($search) $query  = $repository->searchProductsQuery($search);

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('search/full.html.twig', [
            'products' => $pagination,
            'search'   => $search,
        ]);
    }
}
