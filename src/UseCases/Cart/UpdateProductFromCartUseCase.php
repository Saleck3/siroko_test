<?php

namespace App\UseCases\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\UseCases\Product\GetProductByIdUseCase;
use App\UseCases\Product\ProductNotFoundException;

readonly class UpdateProductFromCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private GetProductByIdUseCase   $productById)
    {
    }

    /**
     * @throws ProductNotFoundException
     */
    public function updateProduct(string $userId, int $productId, int $quantity)
    {
        //Search for existing cart
        $cart = $this->cartRepository->findByUserId($userId);

        if (empty($cart)) {
            //User dosen't have a cart I create one and add the product
            //I create the cart so I can return it
            $cart = new Cart();
            $cart->setUserId($userId);

            $this->createProductAndAddToCart($cart, $productId, $quantity);

            $this->cartRepository->save($cart);
            return $cart;
        }

        $products = $cart->getProducts();

        foreach ($products as $product) {
            if ($product->getProductId() === $productId) {
                if ($quantity <= 0) {
                    $cart->removeProduct($product);
                } else {
                    $product->setQuantity($quantity);
                }
                $this->cartRepository->save($cart);
                return $cart;
            }
        }
        //the product was not originaly in the cart, so I add it
        if ($quantity > 0) {
            $cart = $this->createProductAndAddToCart($cart, $productId, $quantity);
        }

        return $cart;

    }

    /**
     * @param Cart $cart
     * @param int  $productId
     * @param int  $quantity
     * @throws ProductNotFoundException
     * @return void
     */
    public function createProductAndAddToCart(Cart $cart, int $productId, int $quantity): Cart
    {
        if (!$this->validateProduct($productId)) {
            throw new ProductNotFoundException();
        }

        $product = new CartProduct();
        $product->setProductId($productId);
        $product->setQuantity($quantity);

        $cart->addProduct($product);
        $this->cartRepository->save($cart);
        return $cart;
    }

    private function validateProduct(int $productId): bool
    {
        $product = $this->productById->find($productId);

        return !empty($product);
    }

}
