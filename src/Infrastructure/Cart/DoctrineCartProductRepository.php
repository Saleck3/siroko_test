<?php

namespace App\Infrastructure\Cart;

use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CartProduct>
 */
class DoctrineCartProductRepository extends ServiceEntityRepository implements CartProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }

}
