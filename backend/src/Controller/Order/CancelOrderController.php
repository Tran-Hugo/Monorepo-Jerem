<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpFoundation\Request;

#[AsController]
class CancelOrderController extends AbstractController
{
    public function __construct(
        private OrderService $orderService
    ) {}

    public function __invoke(Order $order, Request $request): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);
            $cancellationReason = $data['reason'] ?? null;

            $this->orderService->cancelOrder($order, $cancellationReason);
            return $this->json(['message' => 'Order cancelled successfully.'], 200);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }
}
