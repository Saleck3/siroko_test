<?php

namespace App\UseCases\Order;

use App\Domain\Order\Order;
use App\Domain\Order\OrderRepositoryInterface;
use App\UseCases\Product\GetProductByIdUseCase;

class GetOrderByIdUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private GetProductByIdUseCase    $productById,
    )
    {
    }

    public function getOrder(int $orderId): array
    {
        $order = $this->orderRepository->findOneById($orderId);
        if (empty($order)) {
            //If order dosent exist, throw exception
            throw new OrderNotFoundException();
        }

        $res = array(
            "id" => $order->getId(),
            "userId" => $order->getUserId(),
            "total" => $order->getTotal()
        );
        $products = $order->getProducts();
        for ($i = 0; $i < count($products); $i++) {
            $res["products"][$i]["product"] = $this->productById->find($products[$i]->getProductId());
            $res["products"][$i]["product"]->setPrice($products[$i]->getPrice());
            $res["products"][$i]["quantity"] = $products[$i]->getQuantity();
        }

        return $res;
    }
}
