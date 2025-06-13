<?php

namespace App\Domain\Order;

interface OrderRepositoryInterface
{

    public function findOneById(int $id): ?Order;

    public function findByUserId(string $userId): ?array;
}
