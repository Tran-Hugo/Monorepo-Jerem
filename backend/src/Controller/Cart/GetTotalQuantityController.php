<?php

namespace App\Controller\Cart;
use RuntimeException;
use App\Service\CartService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class GetTotalQuantityController
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly Security $security,
    ) {}

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function __invoke(): JsonResponse
    {
        $user = $this->security->getUser();
        if (!$user) {
            throw new RuntimeException('Unauthorized');
        }
        $count = $this->cartService->getItemCount($user);

        return new JsonResponse(json_encode(["count"=>$count]), 200, [], true);
    }
}