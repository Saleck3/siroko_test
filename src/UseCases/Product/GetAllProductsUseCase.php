<?php

namespace App\UseCases\Product;

use App\Domain\Product\ProductRepositoryInterface;

readonly class GetAllProductsUseCase
{

    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    public function getAll(): array
    {
        return $this->productRepository->findAll();
    }
}
