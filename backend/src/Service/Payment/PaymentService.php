<?php

namespace App\Service\Payment;

use App\Entity\Order;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Payment\PaymentStrategyInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaymentService
{
    /**
     * @param iterable<PaymentStrategyInterface> $strategies
     */
    public function __construct(
        private readonly iterable $strategies
    ) {}

    /**
     * Crée une session de paiement via la stratégie choisie.
     *
     * @param string $method ex: 'stripe' ou 'paypal'
     * @param array $orderData
     * @return array contenant URL de redirection vers le fournisseur (Stripe) | identifiant de commande (PayPal)
     *
     * @throws \RuntimeException
     */
    public function createPaymentSession(string $method, Order $orderData): array
    {
        $strategy = $this->getStrategyByName($method);

        if (!$strategy) {
            throw new \RuntimeException("Méthode de paiement '$method' non supportée.");
        }

        return $strategy->createCheckoutSession($orderData);
    }

    private function getStrategyByName(string $name): ?PaymentStrategyInterface
    {
        foreach ($this->strategies as $strategy) {
            if ($strategy->getName() === $name) {
                return $strategy;
            }
        }

        return null;
    }

    public function handleWebhookByRequest(Request $request): JsonResponse
    {
        $strategy = $this->detectStrategyFromRequest($request);

        if (!$strategy) {
            return new JsonResponse(['error' => 'Unknown payment provider'], 400);
        }

        return $strategy->handleWebhook($request);
    }

    private function detectStrategyFromRequest(Request $request): ?PaymentStrategyInterface
    {
        $type = $request->query->get('type');
        
        // Stripe a ce header unique
        if ($request->headers->has('Stripe-Signature')) {
            return $this->getStrategyByName('stripe');
        }

        // PayPal utilise ces headers typiques
        if ($request->headers->has('Paypal-Transmission-Id') || $type == "paypal") {
            return $this->getStrategyByName('paypal');
        }

        return null;
    }
}
