<?php

namespace App\Repository;

use App\Entity\UserExtra;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method UserExtra|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserExtra|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserExtra[]    findAll()
 * @method UserExtra[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserExtraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, UserExtra::class);
    }

}
