<?php

namespace App\Controller;

use App\UseCases\Product\GetAllProductsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(GetAllProductsUseCase $getAllProductsUseCase): JsonResponse
    {
        $products = $getAllProductsUseCase->getAll();
        return $this->json($products);
    }
}
