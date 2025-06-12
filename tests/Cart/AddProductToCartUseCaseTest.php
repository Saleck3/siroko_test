<?php

namespace App\Tests\Cart;

use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\Tests\Factory\Product\ProductFactory;
use App\UseCases\Cart\AddProductToCartUseCase;
use App\UseCases\Product\GetProductByIdUseCase;
use App\UseCases\Product\ProductNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class AddProductToCartUseCaseTest extends WebTestCase
{
    use ResetDatabase, Factories;

    private AddProductToCartUseCase $addProductToCart;

    public function setUp(): void
    {
        $cartRepository = $this->getContainer()->get(CartRepositoryInterface::class);
        $getProductByIdUseCase = $this->getContainer()->get(GetProductByIdUseCase::class);
        $this->addProductToCart = new AddProductToCartUseCase($cartRepository, $getProductByIdUseCase);
    }

    public function testCanAddProductToCart(): void
    {
        //Given a user and a product
        $user = "testUser";
        $product = ProductFactory::createOne();


        //When adding the product to a cart
        try {
            $cart = $this->addProductToCart->addProduct($user, $product->getId());
        } catch (ProductNotFoundException $e) {
            $this->fail($e->getMessage());
        }

        //Then the cart is created and have one product
        $this->assertNotEmpty($cart);
        $this->assertCount(1, $cart->getProducts());

    }

    public function testAddingTwoOfTheSameProductAddToQuantity(): void
    {
        //Given a user and a product
        $user = "testUser";
        $product = ProductFactory::createOne();

        //When adding the product to a cart
        try {
            $this->addProductToCart->addProduct($user, $product->getId());
            $cart = $this->addProductToCart->addProduct($user, $product->getId());
        } catch (ProductNotFoundException $e) {
            $this->fail($e->getMessage());
        }

        //Then the cart is created
        $this->assertNotEmpty($cart);

        //Then Cart has only one product
        $allProducts = $cart->getProducts();
        $this->assertCount(1, $allProducts);

        //Then Product have quantity 2
        $expectedProduct = $allProducts[0];
        $this->assertInstanceOf(CartProduct::class, $expectedProduct);
        $this->assertEquals(2, $expectedProduct->getQuantity());

    }

    public function testCantAddUnexistentProduct(): void
    {

        $this->expectException(ProductNotFoundException::class);

        //Given there are no products
        $user = "testUser";
        $product = 999999;

        //When trying to add a product
        $this->addProductToCart->addProduct($user, $product);

        //Then ProductNotFoundException is throwed

    }
}
