<?php

namespace App\Tests\Product;

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

}
