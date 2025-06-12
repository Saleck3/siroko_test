<?php

namespace App\Tests\Product;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

final class ProductsControllerTest extends WebTestCase
{
    use ResetDatabase;

    public function testControllerReturns200(): void
    {
        //Given a client
        $client = $this->createClient();

        //When making the http call
        $client->request('GET', '/products/');

        //then response is 200
        $this->assertResponseIsSuccessful();
    }

}
