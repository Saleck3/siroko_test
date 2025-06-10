<?php

namespace App\Tests\Controller;

use App\Domain\Product\ProductRepositoryInterface;
use App\Tests\Factory\Product\ProductFactory;
use App\UseCases\Product\GetAllProductsUseCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ProductsControllerTest extends WebTestCase
{
    use ResetDatabase;

    public function testIndex(): void
    {
        $client = static::createClient();
        $client->request('GET', '/products');

        self::assertResponseIsSuccessful();
    }

    public function testCanRetrieveProducts(): void
    {
        $expected = 2;
        //Given expected products
        for ($i = 0; $i < $expected; $i++) {
            ProductFactory::createOne();
        }

        //When retrieving products
        $productRepository = $this->getContainer()->get(ProductRepositoryInterface::class);
        $productsUseCase = new GetAllProductsUseCase($productRepository);
        $allProducts = $productsUseCase->getAll();

        //Then products quantity is the same
        self::assertEquals($expected, count($allProducts));
    }
}
