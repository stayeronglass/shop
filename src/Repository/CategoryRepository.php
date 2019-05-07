<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\ORM\QueryBuilder;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Param;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends NestedTreeRepository
{

    public function getMainCategories() : array
    {
        return $this->getCategoriesQuery()
            ->andWhere('cat.main = :main')
            ->setParameter('main', 1)
            ->getQuery()
            ->getArrayResult()
        ;
    }

    public function getCategories(QueryBuilder $cqb = null): array
    {
        $q = $this->getCategoriesQuery();

        if (null !== $cqb) {
            $q->andWhere(
                $q->expr()->in(
                    'cat.id',
                    $cqb->getDQL()
                ))->setParameters($cqb->getParameters())
            ;
        }


        return $q->getQuery()->getArrayResult();
    }


    public function getCategoriesQuery()
    {
        return $this->createQueryBuilder('cat')
            ->orderBy('cat.id', 'ASC')
        ;
    }
}
