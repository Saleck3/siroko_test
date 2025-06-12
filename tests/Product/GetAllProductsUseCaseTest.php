<?php

namespace App\Tests\Product;

use App\Domain\Product\ProductRepositoryInterface;
use App\Tests\Factory\Product\ProductFactory;
use App\UseCases\Product\GetAllProductsUseCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class GetAllProductsUseCaseTest extends WebTestCase
{
    use ResetDatabase, Factories;

    public function testCanRetrieveProducts(): void
    {
        $expected = 2;
        //Given expected products

        ProductFactory::createMany($expected);

        //When retrieving products
        $productRepository = $this->getContainer()->get(ProductRepositoryInterface::class);
        $productsUseCase = new GetAllProductsUseCase($productRepository);
        $allProducts = $productsUseCase->getAll();

        //Then products quantity is the same
        $this->assertCount($expected, $allProducts);
    }
}
