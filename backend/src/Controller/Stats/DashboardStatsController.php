<?php

namespace App\Controller\Stats;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DashboardStatsController extends AbstractController
{
    public function __construct(
        private OrderRepository $orderRepository,
        private ProductRepository $productRepository
    ) {}

    #[Route('/api/stats/dashboard', name: 'dashboard_stats', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $paidOrdersCount = $this->orderRepository->count(['status' => 'paid']);
        $shippedOrdersCount = $this->orderRepository->count(['status' => 'shipped']);
        $productsCount = $this->productRepository->count([]);

        return $this->json([
            'paidOrders' => $paidOrdersCount,
            'shippedOrders' => $shippedOrdersCount,
            'products' => $productsCount,
        ]);
    }
}
