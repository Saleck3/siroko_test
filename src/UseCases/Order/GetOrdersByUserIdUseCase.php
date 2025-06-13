<?php

namespace App\UseCases\Order;

use App\Domain\Order\OrderRepositoryInterface;
use App\UseCases\Product\GetProductByIdUseCase;

class GetOrdersByUserIdUseCase
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
        private GetProductByIdUseCase    $productById,
    )
    {
    }

    public function getOrders(string $userId): array
    {
        $allOrders = $this->orderRepository->findByUserId($userId);

        if (empty($allOrders)) {
            //If order dosent exist, throw exception
            throw new OrderNotFoundException();
        }
        $res = array();
        foreach ($allOrders as $order) {
            $outputOrder = array(
                "id" => $order->getId(),
                "userId" => $order->getUserId(),
                "total" => $order->getTotal()
            );
            $products = $order->getProducts();
            for ($i = 0; $i < count($products); $i++) {
                $outputOrder["products"][$i]["product"] = $this->productById->find($products[$i]->getProductId());
                $outputOrder["products"][$i]["product"]->setPrice($products[$i]->getPrice());
                $outputOrder["products"][$i]["quantity"] = $products[$i]->getQuantity();
            }
            $res[] = $outputOrder;
        }


        return $res;
    }

}
