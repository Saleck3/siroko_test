<?php

namespace App\UseCases\Order;

use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Order\Order;
use App\Domain\Order\OrderProduct;
use App\Domain\Order\OrderRepositoryInterface;
use App\UseCases\Cart\EmptyCartUseCase;
use App\UseCases\Cart\GetCartUseCase;
use App\UseCases\Product\GetProductByIdUseCase;

readonly class CreateOrderUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private GetCartUseCase           $getCart,
        private GetProductByIdUseCase    $productById,
        private EmptyCartUseCase         $emptyCart
    )
    {
    }

    /**
     * @throws UserHasEmptyCartException
     */
    public function create(string $userId): Order
    {
        $cart = $this->getCart->getCart($userId);

        if (count($cart["products"]) <= 0) {
            throw new UserHasEmptyCartException();
        }

        $order = new Order();
        $order->setUserId($userId);

        //TODO: refactor to a map or something, there should be a better way to do this
        foreach ($cart["products"] as $inputProduct) {
            $this->addProductToOrder($order, $inputProduct);
        }

        $this->orderRepository->save($order);
        $this->emptyCart->emptyCart($userId);

        return $order;
    }

    /**
     * @param array $inputProduct
     * @param Order $order
     * @return void
     */
    public function addProductToOrder(Order $order, array $inputProduct): void
    {
        $product = $this->productById->find($inputProduct["product"]->getId());

        $orderProduct = new OrderProduct();
        $orderProduct->setProductId($product->getId());
        $orderProduct->setQuantity($inputProduct["quantity"]);
        $orderProduct->setPrice($product->getPrice());

        $order->addProduct($orderProduct);
    }

}
