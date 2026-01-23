<?php

namespace App\Controller\ShippingMethod;

use App\Service\ShippingMethodService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class DeleteShippingMethodController
{
    public function __construct(
        private readonly ShippingMethodService $shippingMethodService
    ){}
    
    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request): JsonResponse
    {
        $id = $request->attributes->get('id');
        $this->shippingMethodService->deleteShippingMethod($id);
        return new JsonResponse(['status' => 'Shipping method deleted successfully'], 200);
    }
}