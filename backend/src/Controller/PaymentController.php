<?php

namespace App\Controller;

use App\Service\OrderService;
use App\Service\Payment\PaymentService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly OrderService $orderService,
        private readonly Security $security,
    ) {}

    #[IsGranted('ROLE_USER')]
    #[Route('/api/payment/create', name: 'payment_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);

        try {
            $order = $this->orderService->getOrder($data, $user);
            $result = $this->paymentService->createPaymentSession(
                $data['method'] ?? '',
                $order
            );

            return new JsonResponse($result);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[Route('/api/payment/webhooks', name: 'payment_webhooks', methods: ['POST'])]
    public function handleWebhooks(Request $request): JsonResponse
    {
        return $this->paymentService->handleWebhookByRequest($request);
    }
}
