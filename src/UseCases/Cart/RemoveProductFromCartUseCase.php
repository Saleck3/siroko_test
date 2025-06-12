<?php

namespace App\UseCases\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartRepositoryInterface;
use App\UseCases\Product\GetProductByIdUseCase;
use App\UseCases\Product\ProductNotFoundException;

class RemoveProductFromCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $cartRepository)
    {
    }

    public function removeProduct(string $userId, int $productId): Cart
    {

        //Search for existing cart
        $cart = $this->cartRepository->findByUserId($userId);

        if (empty($cart)) {
            //User dosen't have a cart, theres no products to remove
            //I create the cart so I can return it
            $cart = new Cart();
            $cart->setUserId($userId);
            $this->cartRepository->save($cart);
            return $cart;

        }

        $products = $cart->getProducts();

        foreach ($products as $product) {
            if ($product->getProductId() === $productId) {
                $cart->removeProduct($product);
                break;
            }
        }


        $this->cartRepository->save($cart);
        return $cart;
    }
}
