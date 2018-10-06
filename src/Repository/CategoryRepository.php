<?php

namespace App\Repository;

use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Gedmo\Tree\Entity\Repository\NestedTreeRepository;
use Doctrine\ORM\Query\Expr;

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
            ->getResult(Query::HYDRATE_ARRAY)
        ;
    }


}
