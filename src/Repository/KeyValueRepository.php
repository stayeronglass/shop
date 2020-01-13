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

    public function getValue(string $key) : string
    {
        return $this->createQueryBuilder('kv')
            ->select('kv.value')
            ->where('kv.key = :key')
            ->setParameter('key', $key)
            ->getQuery()
            ->getSingleScalarResult()['value']
        ;

    }

    public function getItems(array $keys): array
    {
        $qb = $this->createQueryBuilder('kv')
            ->select('kv.key, kv.value')
        ;
        $data = $qb->andWhere(
            $qb->expr()->in('kv.key', $keys))
            ->getQuery()
            ->getArrayResult();

        $result = [];
        foreach ($keys as $key):
            $result[$key] = '';
        endforeach;

        foreach ($data as $item):
            $result[$item['key']] = $item['value'];
        endforeach;

        return $result;
    }
}
