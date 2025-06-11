<?php

namespace App\UseCases\Product;

use App\Domain\Product\Product;
use App\Domain\Product\ProductRepositoryInterface;

readonly class GetProductByIdUseCase
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function find(int $productId): ?Product
    {
        return $this->productRepository->findOneById($productId);
    }
}
