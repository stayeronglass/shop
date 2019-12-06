<?php

namespace App\Repository;

use App\Entity\Cart;
use App\Entity\Image;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Param;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;
use Symfony\Bridge\Doctrine\RegistryInterface;
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

    public function getCartByUser(int $uid) : array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id')
            ->andWhere('c.user_id = :user_id')
            ->setParameter('user_id', $uid)
            ->getQuery()
            ->getScalarResult()
            ;
    }

    public function getFullCartByUser(int $uid): array
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.amount, p.id as pid, p.title, p.price, i.name as image_name, i.ext as image_ext ')
            ->innerJoin('c.product', 'p')
            ->innerJoin('p.images', 'i',Expr\Join::WITH, 'i.main = :image_main')
            ->setParameter('image_main', Image::MAIN_IMAGE)
            ->andWhere('c.user_id = :user_id')
            ->setParameter('user_id', $uid)
            ->orderBy('c.id', 'ASC')
            ->getQuery()
            ->getScalarResult()
            ;
    }

    public function clearCartByUser(int $uid): array
    {
        return $this->createQueryBuilder('c')
            ->delete()
            ->where('c.user_id = :user_id')
            ->setParameter('user_id' ,$uid)
            ->getQuery()
            ->getScalarResult()
        ;
    }

    public function getCartAmountByUser(int $uid) : int
    {
        $res =  $this->createQueryBuilder('c')
            ->select('sum(c.amount) as amount')
            ->andWhere('c.user_id = :user_id')
            ->setParameter('user_id', $uid)
            ->getQuery()
            ->getScalarResult();

        return $res[0]['amount'] ?? 0;
    }

    public function add(int $amount, Product $product, User $user): bool
    {
        $cart = $this->findOneBy(['product_id' => $product->getId(), 'user_id' => $user->getId()]);

        $cart = $cart ??  new Cart();
        $cart
            ->setUser($user)
            ->setProduct($product)
            ->setAmount($cart->getAmount() + $amount)
        ;

        $this->_em->persist($cart);
        $this->_em->flush();

        return true;
    }
}
