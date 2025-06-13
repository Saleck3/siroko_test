<?php

namespace App\Tests\Order;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Order\OrderRepositoryInterface;
use App\Tests\Factory\Cart\CartFactory;
use App\Tests\Factory\Product\ProductFactory;
use App\UseCases\Cart\EmptyCartUseCase;
use App\UseCases\Cart\GetCartUseCase;
use App\UseCases\Order\CreateOrderUseCase;
use App\UseCases\Order\UserHasEmptyCartException;
use App\UseCases\Product\GetProductByIdUseCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CreateOrderUseCaseTest extends WebTestCase
{
    use ResetDatabase, Factories;

    private CreateOrderUseCase $createOrder;
    private CartRepositoryInterface $cartRepository;

    public function setUp(): void
    {
        $this->cartRepository = $this->getContainer()->get(CartRepositoryInterface::class);
        $orderRepository = $this->getContainer()->get(OrderRepositoryInterface::class);
        $getCart = $this->getContainer()->get(GetCartUseCase::class);
        $productById = $this->getContainer()->get(GetProductByIdUseCase::class);
        $emptyCart = $this->getContainer()->get(EmptyCartUseCase::class);
        $this->createOrder = new CreateOrderUseCase($orderRepository, $getCart, $productById, $emptyCart);
    }

    public function testCanCreateOrder(): void
    {
        //Given a non empty cart (with an existing product)
        $product = ProductFactory::createOne();

        $cartProduct = new cartProduct();
        $cartProduct->setProductId($product->getId());
        $cartProduct->setQuantity(1);

        $userId = "testUser";
        $cart = new Cart();
        $cart->setUserId($userId);
        $cart->addProduct($cartProduct);
        $this->cartRepository->save($cart);

        //When creating the order
        try {
            $order = $this->createOrder->create($userId);
        } catch (UserHasEmptyCartException $e) {
            $this->fail($e->getMessage());
        }

        //Then the orden is created and has a product
        $this->assertNotEmpty($order);
        $this->assertCount(1, $order->getProducts());
    }

    public function testCantCreateOrderWithEmptyCart(): void
    {
        $this->expectException(UserHasEmptyCartException::class);

        //Given user has empty cart
        $cart = CartFactory::createOne();

        //When creating the order
        $this->createOrder->create($cart->getId());

        //Then UserHasEmptyCartException is thrown
    }
}
