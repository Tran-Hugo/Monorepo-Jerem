<?php

namespace App\Controller\Cart;
use App\Service\CartService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class GetCartController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly Security $security,
        private readonly SerializerInterface $serializer
    ) {}

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function __invoke(): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }

        $responseData = $this->cartService->getCartSummary($user);

        $json = $this->serializer->serialize($responseData, 'json', ['groups' => ['cart:read']]);

        return new JsonResponse($json, 200, [], true);
    }
}