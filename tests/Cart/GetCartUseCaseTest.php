<?php

namespace App\Tests\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\UseCases\Cart\GetCartUseCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class GetCartUseCaseTest extends WebTestCase
{
    use ResetDatabase, Factories;

    private CartRepositoryInterface $cartRepository;
    private GetCartUseCase $getCart;

    public function setUp(): void
    {
        $this->cartRepository = $this->getContainer()->get(CartRepositoryInterface::class);
        $this->getCart = $this->getContainer()->get(GetCartUseCase::class);
    }

    public function testCanRecoverAProduct(): void
    {
        //Given a non empty cart
        $product = new cartProduct();
        $product->setProductId(1);
        $product->setQuantity(1);

        $userId = "testUser";
        $expectedCart = new Cart();
        $expectedCart->setUserId($userId);
        $expectedCart->addProduct($product);
        $this->cartRepository->save($expectedCart);

        //When recovering the cart
        $cart = $this->getCart->getCart($userId);

        //Then the cart has products
        $this->assertNotEmpty($cart);
        $this->assertCount(1, $cart["products"]);
    }

    public function testCanReturnEmptyCart(): void
    {
        //Given a user with no cart
        $userId = "testUserWithNoCart";

        //When requesting the cart
        $cart = $this->getCart->getCart($userId);

        //Then an empty cart is returned
        $this->assertNotEmpty($cart);
        $this->assertCount(0, $cart["products"]);
    }
}
