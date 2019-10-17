<?php

namespace App\Repository\My;

use App\Entity\My\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Query;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Address::class);
    }

    public function getUserAddressQuery(int $uid) : Query
    {
        return $this->_em->createQuery('
            SELECT a.id, a.address FROM App\Entity\My\Address a WHERE a.user_id = :user_id 
            ORDER BY a.id ASC
        ')
            ->setParameter('user_id', $uid, Type::INTEGER)
        ;
    }

    public function getUserAddress(int $uid) : array
    {
        return $this->getUserAddressQuery($uid)->getScalarResult();
    }

}
