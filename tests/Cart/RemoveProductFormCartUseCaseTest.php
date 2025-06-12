<?php

namespace App\Tests\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\UseCases\Cart\RemoveProductFromCartUseCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class RemoveProductFormCartUseCaseTest extends WebTestCase
{
    use ResetDatabase, Factories;

    private CartRepositoryInterface $cartRepository;
    private RemoveProductFromCartUseCase $removeProductFormCart;

    public function setUp(): void
    {
        $this->cartRepository = $this->getContainer()->get(CartRepositoryInterface::class);
        $this->removeProductFormCart = new RemoveProductFromCartUseCase($this->cartRepository);
    }

    public function testCanRemoveProduct(): void
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

        //When removing the product
        $this->removeProductFormCart->removeProduct($userId, $product->getProductId());

        //Then the cart is empty
        $this->assertCount(0, $cart->getProducts());
    }

    public function testOnlyRemovesExpectedProduct(): void
    {
        //Given cart with two products
        $product = new cartProduct();
        $product->setProductId(1);
        $product->setQuantity(1);

        $product2 = new cartProduct();
        $product2->setProductId(2);
        $product2->setQuantity(1);

        $userId = "testUser";
        $cart = new Cart();
        $cart->setUserId($userId);
        $cart->addProduct($product);
        $cart->addProduct($product2);
        $this->cartRepository->save($cart);

        //When removing one product
        $this->removeProductFormCart->removeProduct($userId, $product->getProductId());

        //Then the cart has one product left
        $this->assertCount(1, $cart->getProducts());
    }

    public function testCanRemoveAnInexistentProdct(): void
    {
        //Given an empty cart
        $userId = "testUser";
        $cart = new Cart();
        $cart->setUserId($userId);
        $this->cartRepository->save($cart);

        //When removing any product
        $this->removeProductFormCart->removeProduct($userId, 1);

        //Then the cart is empty (and no exception)
        $this->assertCount(0, $cart->getProducts());
    }
}
