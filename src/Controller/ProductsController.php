<?php

namespace App\Controller;

use App\UseCases\Product\GetAllProductsUseCase;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductsController extends AbstractController
{
    #[Route('/products', name: 'app_products')]
    public function index(GetAllProductsUseCase $getAllProductsUseCase, SerializerInterface $serializer): JsonResponse
    {
        $products = $getAllProductsUseCase->getAll();
        return $this->json($serializer->serialize($products, 'json'));
    }
}
