<?php

namespace App\Domain\Product;

use App\Domain\Product;

interface ProductRepositoryInterface
{
    public function findOneById(int $id): ?Product;
}
