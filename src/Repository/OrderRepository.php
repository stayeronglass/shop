<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\DBAL\Types\Type;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order[]    findAll()
 * @method Order[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function getOrdersQueryByUser($uid)
    {
        return $this->_em->createQuery('
            SELECT o.id, o.createdAt, o.total, os.title AS status, o.status_id 
            FROM App\Entity\Order o 
            INNER JOIN o.orderStatus os
            WHERE o.user_id = :user_id 
            ORDER BY o.id DESC
        ')
            ->setParameter('user_id', $uid, Type::INTEGER)
            ;
    }
}
