<?php

namespace App\Controller\Order;

use App\Entity\Order;
use App\Service\OrderService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class ShipOrderController extends AbstractController
{
    public function __construct(private OrderService $orderService)
    {
    }

    public function __invoke(Order $order): JsonResponse
    {
        $this->orderService->shipOrder($order);
        return $this->json($order, 200, [], ['groups' => ['order:read']]);
    }
}
