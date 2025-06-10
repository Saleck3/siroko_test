<?php

namespace App\Controller;

use App\UseCases\Cart\AddProductToCartUseCase;
use App\UseCases\Product\ProductNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use function PHPUnit\Framework\isNumeric;

#[Route('/cart')]
class CartController extends AbstractController
{
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

        if (!isNumeric($parameters['productId'])) {
            return new JsonResponse(['error' => 'productId must be a number'], 400);
        }

        try {
            $addProductToCart->addProduct($parameters['userId'], $parameters['productId']);
        } catch (ProductNotFoundException $e) {
            return $this->json(['error' => "Product not found"], 404);
        }

        return $this->json(['success' => true]);
    }


}
