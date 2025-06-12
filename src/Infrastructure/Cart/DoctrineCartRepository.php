<?php

namespace App\Infrastructure\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Cart>
 */
class DoctrineCartRepository extends ServiceEntityRepository implements CartRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Cart::class);
    }

    public function save(Cart $cart): Cart
    {
        return $this->add($cart, true);
    }

    public function add(Cart $cart, bool $flush = false): Cart
    {
        $this->getEntityManager()->persist($cart);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $cart;
    }

    public function findByUserId(string $userId): ?Cart
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.userID = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
