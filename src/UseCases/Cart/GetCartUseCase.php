<?php

namespace App\UseCases\Cart;

use App\Domain\Cart\CartRepositoryInterface;
use App\Domain\Product\ProductRepositoryInterface;

readonly class GetCartUseCase
{

    public function __construct(private CartRepositoryInterface $cartRepository, private ProductRepositoryInterface $productRepository)
    {
    }

    public function getCart(string $userId): array
    {
        //Search for existing cart
        $cart = $this->cartRepository->findByUserId($userId);
        $res = array("userId" => $userId);
        $res["products"] = array();

        if (empty($cart)) {
            //If user never had a cart, I return a dummy
            return $res;
        }

        $products = $cart->getProducts();
        for ($i = 0; $i < count($products); $i++) {
            $res["products"][$i]["product"] = $this->productRepository->findOneById($products[$i]->getProductId());
            $res["products"][$i]["quantity"] = $products[$i]->getQuantity();
        }

        return $res;
    }

}
