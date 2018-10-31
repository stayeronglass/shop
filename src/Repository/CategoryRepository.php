<?php

namespace App\Repository;

use App\Entity\Category;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends NestedTreeRepository
{

    public function getMainCategories(){
        return $this->createQueryBuilder('p')
            ->andWhere('p.main = :main')
            ->setParameter('main', 1)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getArrayResult()
        ;
    }


}
