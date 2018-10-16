<?php

namespace App\Repository;

use App\Entity\Cart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr;

/**
 * @method Cart|null find($id, $lockMode = null, $lockVersion = null)
 * @method Cart|null findOneBy(array $criteria, array $orderBy = null)
 * @method Cart[]    findAll()
 * @method Cart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function getCartByUser($uid){
        return $this->createQueryBuilder('c')
            ->select('c.id')
            ->andWhere('c.user_id = :user_id')
            ->setParameter('user_id', $uid)
            ->getQuery()
            ->getResult(Query::HYDRATE_SCALAR);
    }

    public function getFullCartByUser($uid){
        return $this->createQueryBuilder('c')
            ->select('c.id, c.amount, p.id as pid, p.title, p.price, i.name as image_name, i.ext as image_ext ')
            ->innerJoin('c.product', 'p')
            ->innerJoin('p.images', 'i',Expr\Join::WITH, 'i.main = 1')
            ->andWhere('c.user_id = :user_id')
            ->setParameter('user_id', $uid)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getResult(Query::HYDRATE_SCALAR);
    }
}
