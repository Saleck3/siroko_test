<?php

namespace App\Controller;

use App\UseCases\Cart\AddProductToCartUseCase;
use App\UseCases\Cart\EmptyCartUseCase;
use App\UseCases\Cart\GetCartUseCase;
use App\UseCases\Cart\RemoveProductFromCartUseCase;
use App\UseCases\Cart\UpdateProductFromCartUseCase;
use App\UseCases\Product\ProductNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart')]
class CartController extends AbstractController
{

    #[Route('/{userId}', name: 'app_cart_get', methods: ['GET'])]
    public function getUserCart(string $userId, GetCartUseCase $getCart): JsonResponse
    {
        $cart = $getCart->getCart($userId);
        return $this->json($cart);
    }

    #[Route('/addProduct', name: 'app_cart_addProduct', methods: ['POST'])]
    public function addProduct(Request $request, AddProductToCartUseCase $addProductToCart): JsonResponse
    {
        $parameters = $request->toArray();

        if (empty($parameters['userId'])) {
            return new JsonResponse(['error' => 'userId is required'], 400);
        }

        if (empty($parameters['productId'])) {
            return new JsonResponse(['error' => 'productId is required'], 400);
        }

        if (!is_numeric($parameters['productId'])) {
            return new JsonResponse(['error' => 'productId must be a number'], 400);
        }

        try {
            $addProductToCart->addProduct($parameters['userId'], $parameters['productId']);
        } catch (ProductNotFoundException $e) {
            return $this->json(['error' => "Product not found"], 404);
        }

        return $this->json(['success' => true]);
    }

    #[Route('/removeProduct', name: 'app_cart_removeProduct', methods: ['POST'])]
    public function removeProduct(Request $request, RemoveProductFromCartUseCase $removeProductFromCart)
    {
        $parameters = $request->toArray();

        if (empty($parameters['userId'])) {
            return new JsonResponse(['error' => 'userId is required'], 400);
        }

        if (empty($parameters['productId'])) {
            return new JsonResponse(['error' => 'productId is required'], 400);
        }

        if (!is_numeric($parameters['productId'])) {
            return new JsonResponse(['error' => 'productId must be a number'], 400);
        }

        $removeProductFromCart->removeProduct($parameters['userId'], $parameters['productId']);

        return $this->json(['success' => true]);
    }

    #[Route('/updateProduct', name: 'app_cart_updateProduct', methods: ['POST'])]
    public function updateProduct(Request $request, UpdateProductFromCartUseCase $updateProductFromCart): JsonResponse
    {
        $parameters = $request->toArray();

        if (empty($parameters['userId'])) {
            return new JsonResponse(['error' => 'userId is required'], 400);
        }

        if (empty($parameters['productId'])) {
            return new JsonResponse(['error' => 'productId is required'], 400);
        }

        if (!is_numeric($parameters['productId'])) {
            return new JsonResponse(['error' => 'productId must be a number'], 400);
        }

        if (empty($parameters['quantity'])) {
            return new JsonResponse(['error' => 'quantity is required'], 400);
        }

        if (!is_numeric($parameters['quantity'])) {
            return new JsonResponse(['error' => 'quantity must be a number'], 400);
        }

        try {
            $updateProductFromCart->updateProduct($parameters['userId'], $parameters['productId'], $parameters['quantity']);
        } catch (ProductNotFoundException $e) {
            return $this->json(['error' => "Product not found"], 404);
        }

        return $this->json(['success' => true]);
    }

    #[Route('/empty', name: 'app_cart_empty', methods: ['POST'])]
    public function emptyCart(Request $request, EmptyCartUseCase $emptyCartUseCase): JsonResponse
    {
        $parameters = $request->toArray();

        if (empty($parameters['userId'])) {
            return new JsonResponse(['error' => 'userId is required'], 400);
        }

        $emptyCartUseCase->emptyCart($parameters['userId']);

        return $this->json(['success' => true]);
    }
}
