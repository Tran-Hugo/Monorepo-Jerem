<?php

namespace App\Controller\Cart;

use App\Service\CartService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class AddToCartController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly Security $security
    ) {}

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function __invoke(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['productId']) || !isset($data['quantity'])) {
            return new JsonResponse(['error' => 'Invalid input'], 400);
        }

        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $this->cartService->addToCart($user, $data['productId'], $data['quantity']);

        return new JsonResponse(['status' => 'Item added to cart successfully'], 201);
    }
}