<?php

namespace App\Tests\Cart;

use App\Tests\Factory\Product\ProductFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\ResetDatabase;

class CartControllerTest extends WebTestCase
{
    use ResetDatabase;

    public function testControllerReturns200(): void
    {
        //Given an existing product and client
        $product = ProductFactory::createOne();
        $client = $this->createClient();

        //When making the http call
        $client->request(
            'POST',
            '/cart/addProduct',
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{
                "userId": "testUser",
                "productId": ' . $product->getId() . '
            }'
        );

        //then response is 200
        $this->assertResponseIsSuccessful();
    }

}
