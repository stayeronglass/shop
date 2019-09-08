<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use http\QueryString;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\Expr;


/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SearchRepository extends ServiceEntityRepository
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function searchProductsQuery(?string $q) : array
    {
        $this->getEntityManager()->getConnection()->quote(trim($q));

        return $this->createQueryBuilder('p')
            ->select("MATCH(p.title) AGAINST('($q*) (\"$q\")') as relevance, p.title, p.id, p.price, p.salePrice as sale_price, i.name as image_name, i.ext as image_ext ")
            ->innerJoin('p.images', 'i',Expr\Join::WITH, 'i.main = 1')
            ->having('relevance > 0')
            ->setMaxResults(100)
            ->orderBy('relevance', 'DESC')
            ->getQuery()
            ->getScalarResult()
            ;
    }
}
