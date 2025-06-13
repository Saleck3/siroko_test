<?php

namespace App\Tests\Order;

use App\Domain\Order\Order;
use App\Domain\Order\OrderProduct;
use App\Domain\Order\OrderRepositoryInterface;
use App\Tests\Factory\Product\ProductFactory;
use App\UseCases\Order\GetOrdersByUserIdUseCase;
use App\UseCases\Order\OrderNotFoundException;
use App\UseCases\Product\GetProductByIdUseCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GetOrdersByUserIdUseCaseTest extends WebTestCase
{

    private OrderRepositoryInterface $orderRepository;
    private GetOrdersByUserIdUseCase $GetOrdersByUserId;

    public function setUp(): void
    {
        $this->orderRepository = $this->getContainer()->get(OrderRepositoryInterface::class);
        $productById = $this->getContainer()->get(GetProductByIdUseCase::class);
        $this->GetOrdersByUserId = new GetOrdersByUserIdUseCase($this->orderRepository, $productById);
    }

    public function testCanRetriveOrder(): void
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
        try {
            $orders = $this->GetOrdersByUserId->getOrders($testUser);
        } catch (OrderNotFoundException $e) {
            $this->fail($e->getMessage());
        }

        //Then order is not empty
        $this->assertNotEmpty($orders);
        $this->assertCount(1, $orders);
        $this->assertCount(1, $orders[0]["products"]);
    }

    public function testCantFindEmptyOrder(): void
    {
        $this->expectException(OrderNotFoundException::class);

        //Given theres no orders
        $userId = "userWthNoOrders";

        //When requesting an order
        $this->GetOrdersByUserId->getOrders($userId);

        //then OrderNotFoundException is thrown

    }
}
