<?php

namespace App\Domain\Product;

interface ProductRepositoryInterface
{
    public function findAll(): array;
}
