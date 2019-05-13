<?php

namespace App\Repository;

use App\Entity\KeyValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method KeyValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method KeyValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method KeyValue[]    findAll()
 * @method KeyValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KeyValueRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, KeyValue::class);
    }

}
