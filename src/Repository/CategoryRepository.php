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

    public function getAllCategories()
    {
        return $this->getChildrenQuery(null, false, 'id')
            ->getScalarResult();
    }

    public function getMainCategories() : array
    {
        return $this->getCategoriesQuery()
            ->andWhere('cat.main = :main')
            ->andWhere('cat.special = :special')
            ->setParameter('special', false)
            ->setParameter('main', 1)
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function getCategories(?QueryBuilder $cqb = null): array
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

        $q
            ->andWhere('cat.special = :special')
            ->setParameter('special', false);

        return $q->getQuery()->getScalarResult();
    }


    public function getCategoriesQuery() : QueryBuilder
    {
        return $this->createQueryBuilder('cat')
            ->select('cat.id, cat.slug, cat.name, cat.description, i.id as image_id, i.name as image_name, i.ext')
            ->leftJoin('cat.image', 'i')
            ->orderBy('cat.id', 'ASC')
        ;
    }
}
