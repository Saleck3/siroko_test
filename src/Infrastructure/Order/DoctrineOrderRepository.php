<?php

namespace App\Infrastructure\Order;

use App\Domain\Order\Order;
use App\Domain\Order\OrderRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class DoctrineOrderRepository extends ServiceEntityRepository implements OrderRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    public function save(Order $order): Order
    {
        return $this->add($order, true);
    }

    public function add(Order $order, bool $flush = false): Order
    {
        $this->getEntityManager()->persist($order);

        if ($flush) {
            $this->getEntityManager()->flush();
        }

        return $order;
    }

    public function findOneById(int $id): ?Order
    {
        /** @var Order|null $qb */
        $qb = $this
            ->createQueryBuilder('p')
            ->where('p.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        return $qb;
    }

    public function findByUserId(string $userId): ?array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.userID = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }
}
