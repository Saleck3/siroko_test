<?php

namespace App\Controller;

use App\UseCases\Order\CreateOrderUseCase;
use App\UseCases\Order\GetOrderByIdUseCase;
use App\UseCases\Order\GetOrdersByUserIdUseCase;
use App\UseCases\Order\OrderNotFoundException;
use App\UseCases\Order\UserHasEmptyCartException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/order')]
class OrderController extends AbstractController
{

    #[Route('/{id}', name: 'app_order_getById', methods: ['GET'])]
    public function getById(GetOrderByIdUseCase $getOrderById, int $id): JsonResponse
    {
        try {
            $order = $getOrderById->getOrder($id);
        } catch (OrderNotFoundException $e) {
            return new JsonResponse(['error' => 'order not found'], 404);
        }
        return $this->json($order);

    }

    #[Route('/user/{userId}', name: 'app_order_getByUserId', methods: ['GET'])]
    public function getByUserId(GetOrdersByUserIdUseCase $getOrdersByUserId, string $userId): JsonResponse
    {
        try {
            $order = $getOrdersByUserId->getOrders($userId);
        } catch (OrderNotFoundException $e) {
            return new JsonResponse(['error' => 'user has no orders'], 404);
        }
        return $this->json($order);

    }

    #[Route('/create', name: 'app_order_create', methods: ['POST'])]
    public function createOrder(Request $request, CreateOrderUseCase $createOrder): JsonResponse
    {
        $parameters = $request->toArray();

        if (empty($parameters['userId'])) {
            return new JsonResponse(['error' => 'userId is required'], 400);
        }

        try {
            $order = $createOrder->create($parameters['userId']);
        } catch (UserHasEmptyCartException $e) {
            return new JsonResponse(['error' => 'the user has no cart'], 404);
        }
        return new JsonResponse($order);
    }
}
