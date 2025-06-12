<?php

namespace App\UseCases\Cart;

use App\Domain\Cart\CartRepositoryInterface;

readonly class EmptyCartUseCase
{
    public function __construct(private CartRepositoryInterface $cartRepository)
    {
    }

    public function emptyCart(string $userId)
    {
        //Search for existing cart
        $cart = $this->cartRepository->findByUserId($userId);

        //User doesn't have a cart
        if (empty($cart)) {
            return null;
        }

        foreach ($cart->getProducts() as $product) {
            $cart->removeProduct($product);
        }

        $this->cartRepository->save($cart);
        return $cart;
    }
}
