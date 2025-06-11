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
        //valido que el producto a agregar exista
        if (!$this->validateProduct($productId)) {
            throw new ProductNotFoundException();
        }

        //obtengo el cart (si existe)
        $cart = $this->cartRepository->findByUserId($userId);

        if (empty($cart)) {
            //User dosen't have a cart, I create one new
            $cart = new Cart();
            $cart->setUserId($userId);

            $this->createProductAndAddToCart($productId, $cart);

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
                $this->createProductAndAddToCart($productId, $cart);
            }
        }
        //guardo en la base
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
    public function createProductAndAddToCart(int $productId, Cart $cart): void
    {
        $product = new CartProduct();
        $product->setProductId($productId);
        $product->setQuantity(1);

        $cart->addProduct($product);
    }
}
