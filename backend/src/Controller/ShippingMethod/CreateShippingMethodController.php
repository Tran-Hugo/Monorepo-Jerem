<?php

namespace App\Controller\ShippingMethod;

use App\Service\ShippingMethodService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class CreateShippingMethodController
{
    public function __construct(
        private readonly ShippingMethodService $shippingMethodService
    ){}

    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request)
    {
        $this->shippingMethodService->createShippingMethod($request);
        return new JsonResponse(['status' => 'Shipping method created successfully'], 201);
    }
}