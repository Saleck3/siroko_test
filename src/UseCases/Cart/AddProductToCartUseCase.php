<?php

namespace App\UseCases\Cart;

use App\Domain\Cart\Cart;
use App\Domain\Cart\CartProduct;
use App\Domain\Cart\CartRepositoryInterface;
use App\UseCases\Product\GetProductByIdUseCase;
use App\UseCases\Product\ProductNotFoundException;

readonly class AddProductToCartUseCase
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
        private GetProductByIdUseCase   $productById)
    {
    }

    /**
     * @throws ProductNotFoundException
     */
    public function addProduct(string $userId, int $productId): Cart
    {
        if (!$this->validateProduct($productId)) {
            throw new ProductNotFoundException();
        }

        //Search for existing cart
        $cart = $this->cartRepository->findByUserId($userId);

        if (empty($cart)) {
            //User dosen't have a cart, I create one new
            $cart = new Cart();
            $cart->setUserId($userId);

            $this->createProductAndAddToCart($cart, $productId);

        } else {

            $products = $cart->getProducts();

            $flag = true;

            //If product already exists add 1 to quantity
            foreach ($products as $product) {
                if ($product->getProductId() === $productId) {
                    $product->setQuantity($product->getQuantity() + 1);
                    $flag = false;
                    break;
                }
            }
            //Else create new product
            if ($flag) {
                $this->createProductAndAddToCart($cart, $productId);
            }
        }

        $this->cartRepository->save($cart);
        return $cart;
    }

    private function validateProduct(int $productId): bool
    {
        $product = $this->productById->find($productId);

        return !empty($product);
    }

    /**
     * @param int  $productId
     * @param Cart $cart
     * @return void
     */
    public function createProductAndAddToCart(Cart $cart, int $productId): void
    {
        $product = new CartProduct();
        $product->setProductId($productId);
        $product->setQuantity(1);

        $cart->addProduct($product);
    }
}
