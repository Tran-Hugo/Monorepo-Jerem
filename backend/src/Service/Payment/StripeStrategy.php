<?php

namespace App\Service\Payment;

use Stripe\Webhook;
use App\Entity\Order;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\Request;
use App\Service\Payment\PaymentStrategyInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeStrategy implements PaymentStrategyInterface
{
    private StripeClient $stripe;
    public function __construct(
        private readonly string $stripeSecretKey,
    ) {
        $this->stripe = new StripeClient($this->stripeSecretKey);
    }

    public function getName(): string
    {
        return 'stripe';
    }

    public function createCheckoutSession(Order $orderData): array
    {
        $session = $this->stripe->checkout->sessions->create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $orderData['currency'],
                    'product_data' => [
                        'name' => $orderData['description'],
                    ],
                    'unit_amount' => (int) ($orderData['total'] * 100),
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $orderData['success_url'],
            'cancel_url' => $orderData['cancel_url'],
        ]);

        return ['url' => $session->url];
    }

    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = $request->getContent();
        $sigHeader = $request->headers->get('Stripe-Signature');
        $secret = $_ENV['STRIPE_WEBHOOK_SECRET'];

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $secret);

            $eventType = $event->type;
            if ($eventType === 'checkout.session.completed') {
                $session = $event->data->object;
                // etc.
            }

            return new JsonResponse(['status' => 'Stripe webhook handled']);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => 'Invalid Stripe webhook'], 400);
        }
    }
}