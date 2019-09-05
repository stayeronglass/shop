<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Image|null find($id, $lockMode = null, $lockVersion = null)
 * @method Image|null findOneBy(array $criteria, array $orderBy = null)
 * @method Image[]    findAll()
 * @method Image[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImageRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Image::class);
    }

    public function getTImages(int $product_id) : array
    {
        return $this->createQueryBuilder('i')
            ->select('i.name, i.ext')
            ->andWhere('i.product_id = :product_id')
            ->addOrderBy('i.main', 'DESC')
            ->addOrderBy('i.id', 'ASC')
            ->setParameter('product_id', $product_id)
            ->getQuery()
            ->getScalarResult()
        ;
    }

}
