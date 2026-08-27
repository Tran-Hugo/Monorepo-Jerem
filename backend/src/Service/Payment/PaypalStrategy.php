<?php

namespace App\Service\Payment;

use App\Entity\Order;
use App\Service\OrderService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class PaypalStrategy implements PaymentStrategyInterface
{
    private string $accessToken;
    private string $apiBase;

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $clientId,
        private readonly string $clientSecret,
        private readonly string $webhookId,
        private readonly bool $sandbox,
        private readonly OrderService $orderService,
        private readonly CacheInterface $cache
    ) {
        $this->apiBase = $this->sandbox
            ? 'https://api-m.sandbox.paypal.com'
            : 'https://api.paypal.com';

        $this->authenticate();
    }

    private function authenticate(): void
    {
        $this->accessToken = $this->cache->get('paypal_access_token', function (ItemInterface $item): string {
            $item->expiresAfter(3500);

            $response = $this->httpClient->request('POST', $this->apiBase.'/v1/oauth2/token', [
                'auth_basic' => [$this->clientId, $this->clientSecret],
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'body' => [
                    'grant_type' => 'client_credentials'
                ],
            ]);

            $data = $response->toArray(false);
            
            if (!isset($data['access_token'])) {
                throw new \RuntimeException('PayPal auth failed: Could not retrieve access token.');
            }

            return $data['access_token'];
        });
    }

    public function getName(): string
    {
        return 'paypal';
    }

    public function createCheckoutSession(Order $orderData): array
    {
        $user = $orderData->getUser();
        $body = [
            'headers' => [
                'Authorization' => 'Bearer '.$this->accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'intent' => 'CAPTURE',
                'payment_source' => [
                    'paypal' => [
                        'experience_context' => [
                            'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                            'landing_page' => 'LOGIN',
                            'shipping_preference' => 'SET_PROVIDED_ADDRESS', // important
                            'user_action' => 'PAY_NOW',
                            'return_url' => 'http://localhost:3000/success', //TODO
                            'cancel_url' => 'http://localhost:3000/cancel',//TODO
                        ]
                    ]
                ],
                'payer' => [
                    'name' => [
                        'given_name' => $orderData->getUser()->getFirstname(),
                        'surname'    => $orderData->getUser()->getLastname(),
                    ],
                    'email_address' => $orderData->getUser()->getEmail(),
                    'address' => [
                        'address_line_1' => $orderData->getBillingAddress()->getStreet(),
                        'admin_area_2' => $orderData->getBillingAddress()->getCity(),
                        'postal_code' => $orderData->getBillingAddress()->getPostalCode(),
                        'country_code' => strtoupper($orderData->getBillingAddress()->getCountry()->getIsoCode()),
                    ],
                    'phone' => [
                        'phone_number' => [
                            'national_number' => $orderData->getBillingAddress()->getPhone(),
                        ],
                    ],
                ],
                'purchase_units' => [
                    [
                        'custom_id' => (string) $orderData->getId(),
                        'amount' => [
                            'currency_code' => "EUR",
                            'value' => number_format($orderData->getTotal() / 100, 2, '.', ''),
                        ],
                        'shipping' => [
                            'name' => [
                                'full_name' => $orderData->getUser()->getFirstname() . ' ' . $orderData->getUser()->getLastname(),
                            ],
                            'address' => [
                                'address_line_1' => $orderData->getShippingAddress()->getStreet(),
                                'address_line_2' => $orderData->getShippingAddress()->getCompany() ?? '',
                                'admin_area_2' => $orderData->getShippingAddress()->getCity(),
                                'admin_area_1' =>'',
                                'postal_code' => $orderData->getShippingAddress()->getPostalCode(),
                                'country_code' => strtoupper($orderData->getShippingAddress()->getCountry()->getIsoCode()),
                            ]
                        ]
                    ]
                ]
            ],
        ];

        $response = $this->httpClient->request('POST', $this->apiBase.'/v2/checkout/orders', $body);

        $data = $response->toArray(false);

        if (!isset($data['id'])) {
            throw new \RuntimeException('Failed to create PayPal checkout session');
        }

        return ['id' => $data['id'], "orderId" => $orderData->getId()];
    }


    public function handleWebhook(Request $request): JsonResponse
    {
        $payload = json_decode($request->getContent(), true);
        $eventType = $payload['event_type'] ?? null;

        if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
            if (!$this->verifyWebhook($request)) {
                return new JsonResponse(['error' => 'Invalid webhook'], 400);
            } 
            $customId = $payload['resource']['custom_id'] ?? null;
            $order = $this->orderService->getById($customId);
            $this->orderService->handlePaymentSuccess($order);
        }

        return new JsonResponse(['status' => 'PayPal webhook handled']);
    }

    public function capturePayment(string $paypalOrderId, Order $order): JsonResponse
    {
        try {
            $response = $this->httpClient->request(
                'POST',
                "{$this->apiBase}/v2/checkout/orders/{$paypalOrderId}/capture",
                [
                    'headers' => [
                        'Authorization' => 'Bearer '.$this->accessToken,
                        'Content-Type'  => 'application/json',
                        // 'PayPal-Mock-Response' => json_encode(["mock_application_codes" => "TRANSACTION_REFUSED"]) MOCK ERRORS
                    ]
                ]
            );

            $result = $response->toArray();

            $capture = $result['purchase_units'][0]['payments']['captures'][0] ?? [];
            $customId = $capture['custom_id'] ?? $result['purchase_units'][0]['custom_id'] ?? null;
            $captureStatus = $capture['status'] ?? $result['status'] ?? null;
            $capturedAmount = $capture['amount']['value'] ?? null;
            $expectedAmount = number_format($order->getTotal() / 100, 2, '.', '');

            if ((string) $customId !== (string) $order->getId()) {
                return new JsonResponse(['error' => 'PayPal order does not match internal order.'], 422);
            }

            if ($captureStatus !== 'COMPLETED') {
                return new JsonResponse(['error' => 'PayPal capture is not completed.'], 422);
            }

            if ($capturedAmount !== $expectedAmount) {
                return new JsonResponse(['error' => 'Captured amount does not match order total.'], 422);
            }

            $this->orderService->handlePaymentSuccess($order);

            return new JsonResponse(['status' => 'Payment captured successfully']);
        } catch (\Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface $e) {
            $errorResponse = $e->getResponse()->toArray(false);
            $debugId = $errorResponse['debug_id'] ?? 'unknown';
            $details = $errorResponse['details'][0]['issue'] ?? 'Unspecified error';

            return new JsonResponse([
                'status' => 'Capture failed',
                'debug_id' => $debugId,
                'issue' => $details
            ], 422);
        }
    }

    private function verifyWebhook(Request $request): bool
    {
        $payload = json_decode($request->getContent(), true);

        $response = $this->httpClient->request(
            'POST',
            $this->apiBase.'/v1/notifications/verify-webhook-signature',
            [
                'headers' => [
                    'Authorization' => 'Bearer '.$this->accessToken,
                    'Content-Type'  => 'application/json',
                ],
                'json' => [
                    'auth_algo'         => $request->headers->get('PayPal-Auth-Algo'),
                    'cert_url'          => $request->headers->get('PayPal-Cert-Url'),
                    'transmission_id'   => $request->headers->get('PayPal-Transmission-Id'),
                    'transmission_sig'  => $request->headers->get('PayPal-Transmission-Sig'),
                    'transmission_time' => $request->headers->get('PayPal-Transmission-Time'),
                    'webhook_id'        => $this->webhookId,
                    'webhook_event'     => $payload,
                ]
            ]
        );

        $result = $response->toArray(false);

        return ($result['verification_status'] ?? null) === 'SUCCESS';
    }
}
