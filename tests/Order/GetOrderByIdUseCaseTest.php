<?php

namespace App\Tests\Order;

use App\Domain\Order\Order;
use App\Domain\Order\OrderProduct;
use App\Domain\Order\OrderRepositoryInterface;
use App\Tests\Factory\Product\ProductFactory;
use App\UseCases\Order\GetOrderByIdUseCase;
use App\UseCases\Order\OrderNotFoundException;
use App\UseCases\Product\GetProductByIdUseCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class GetOrderByIdUseCaseTest extends WebTestCase
{
    use ResetDatabase, Factories;

    private GetOrderByIdUseCase $getOrderById;
    private OrderRepositoryInterface $orderRepository;

    public function setUp(): void
    {
        $this->orderRepository = $this->getContainer()->get(OrderRepositoryInterface::class);
        $productById = $this->getContainer()->get(GetProductByIdUseCase::class);
        $this->getOrderById = new GetOrderByIdUseCase($this->orderRepository, $productById);
    }

    public function testCanRetriveOrderById(): void
    {
        //Given user with an existing order
        $product = ProductFactory::createOne();
        $testUser = "testUser";

        $createdOrder = new Order();
        $createdOrder->setUserID($testUser);
        $orderProduct = new OrderProduct();
        $orderProduct->setProductId($product->getId());
        $orderProduct->setPrice($product->getPrice());
        $createdOrder->addProduct($orderProduct);
        $this->orderRepository->save($createdOrder);

        //When getting the order
        $order = $this->getOrderById->getOrder($createdOrder->getId());

        //Then order is not empty
        $this->assertNotEmpty($order);
        $this->assertCount(1, $order["products"]);
    }

    public function testCantFindEmptyOrder(): void
    {
        $this->expectException(OrderNotFoundException::class);

        //Given theres no orders

        //When requesting an order
        $this->getOrderById->getOrder(1);

        //then OrderNotFoundException is thrown

    }
}
