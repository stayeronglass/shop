<?php

namespace App\Repository;

use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function searchProductsQuery(string $q) : array
    {
        $this->getEntityManager()->getConnection()->quote($q);

        return $this->createQueryBuilder('p')
            ->select("MATCH(p.title) AGAINST('($q*) (\"$q\")' BOOLEAN ) as relevance, p.title, p.id, p.price, p.salePrice as sale_price, p.outOfStock as outofstock, i.name as image_name, i.ext as image_ext ")
            ->innerJoin('p.images', 'i',Expr\Join::WITH, 'i.main = :image_main')
            ->having('relevance > 0')
            ->setMaxResults(100)
            ->orderBy('relevance', 'DESC')
            ->setParameter('image_main', Image::MAIN_IMAGE)
            ->getQuery()
            ->getScalarResult()
            ;
    }
}
