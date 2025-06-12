<?php

namespace App\Tests\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\Tests\Factory\Cart\CartProductFactory;
use App\UseCases\Cart\EmptyCartUseCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class EmptyCartUseCaseTest extends WebTestCase
{
    use ResetDatabase, Factories;

    private EmptyCartUseCase $emptyCart;
    private CartRepositoryInterface $cartRepository;

    public function setUp(): void
    {
        $this->cartRepository = $this->getContainer()->get(CartRepositoryInterface::class);
        $this->emptyCart = new EmptyCartUseCase($this->cartRepository);
    }

    public function testEmptyCart(): void
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

        //When emptying the cart
        $this->emptyCart->emptyCart($cart->getUserID());

        //Then the cart is empty
        $this->assertCount(0, $cart->getProducts());
    }

}
