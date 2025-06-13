<?php

namespace App\Infrastructure\Order;

use App\Domain\Order\OrderProduct;
use App\Domain\Order\OrderProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OrderProduct>
 */
class DoctrineOrderProductRepository extends ServiceEntityRepository implements OrderProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }
}
