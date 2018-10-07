<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query\Expr;

/**
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Product::class);
    }


    public function getSliderProducts(){
        return $this->createQueryBuilder('p')
            ->select('p.id,p.title, i.name,i.ext')
            ->innerJoin('p.images', 'i',Expr\Join::WITH, 'i.main = 1')
            ->andWhere('p.banner = :banner')
            ->setParameter('banner', 1)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        ;
    }

    public function searchProducts($q){
        return $this->searchProductsQuery($q)
            ->getResult(Query::HYDRATE_ARRAY);
        ;
    }

    public function searchProductsQuery($q){
        $this->getEntityManager()->getConnection()->quote($q);

        return $this->createQueryBuilder('p')
            ->select("MATCH(p.title) AGAINST('{$q}') as relevance, p.title, p.id, p.price, i.name as image_name, i.ext as image_ext ")
            ->innerJoin('p.images', 'i',Expr\Join::WITH, 'i.main = 1')
            ->orderBy('relevance', 'DESC')
            ->getQuery()

        ;
    }


    /**
     * @param QueryBuilder $cqb
     *
     * @return Query
     */
    public function getProductByCategory(QueryBuilder $cqb)
    {
        $qb = $this->createQueryBuilder('p')
            ->select("p.title, p.id, p.price, i.name as image_name, i.ext as image_ext, cat.id AS cat_id")
            ->innerJoin('p.images', 'i',Expr\Join::WITH, 'i.main = 1')
            ->innerJoin('p.categories', 'cat')
            ->orderBy('p.createdAt', 'DESC')
        ;
        $qb->andWhere(
            $qb->expr()->in(
                'cat.id',
                $cqb->getDQL()
        ))->setParameters($cqb->getParameters())
        ;

        return $qb->getQuery();
    }
}
