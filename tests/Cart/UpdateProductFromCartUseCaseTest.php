<?php

namespace App\Tests\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\Tests\Factory\Product\ProductFactory;
use App\UseCases\Cart\UpdateProductFromCartUseCase;
use App\UseCases\Product\GetProductByIdUseCase;
use App\UseCases\Product\ProductNotFoundException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class UpdateProductFromCartUseCaseTest extends WebTestCase
{
    use ResetDatabase, Factories;

    private CartRepositoryInterface $cartRepository;
    private UpdateProductFromCartUseCase $updateProductFormCart;

    public function setUp(): void
    {
        $this->cartRepository = $this->getContainer()->get(CartRepositoryInterface::class);
        $getProductByIdUseCase = $this->getContainer()->get(GetProductByIdUseCase::class);
        $this->updateProductFormCart = new UpdateProductFromCartUseCase($this->cartRepository, $getProductByIdUseCase);
    }

    public function testCanUpdateProduct(): void
    {
        //Given a non empty cart
        $product = new cartProduct();
        $product->setProductId(1);
        $product->setQuantity(1);

        $userId = "testUser";
        $cart = new Cart();
        $cart->setUserId($userId);
        $cart->addProduct($product);
        $this->cartRepository->save($cart);

        //When updating the product
        $expectedQuantity = 5;

        try {
            $this->updateProductFormCart->updateProduct($userId, $product->getProductId(), $expectedQuantity);
        } catch (ProductNotFoundException $e) {
            $this->fail($e->getMessage());
        }

        //Then the product has the new quantity
        $this->assertCount(1, $cart->getProducts());
        $this->assertEquals($expectedQuantity, $cart->getProducts()[0]->getQuantity());
    }

    public function testUpdatingAInexistentProductAddsIt(): void
    {
        //Given an empty cart and existing product
        $userId = "testUser";
        $cart = new Cart();
        $cart->setUserId($userId);
        $this->cartRepository->save($cart);

        $product = ProductFactory::createOne();

        //When updating any product
        $expectedQuantity = 69;

        try {
            $this->updateProductFormCart->updateProduct($userId, $product->getId(), $expectedQuantity);
        } catch (ProductNotFoundException $e) {
            $this->fail($e->getMessage());
        }

        //Then the product is added with the correct quantity
        $this->assertCount(1, $cart->getProducts());
        $this->assertEquals($expectedQuantity, $cart->getProducts()[0]->getQuantity());
    }
}
