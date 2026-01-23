<?php

namespace App\Controller\User;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class RegisterController
{
    public function __construct(
        private UserService $userService
    ){}

    public function __invoke(Request $request)
    {
        $result = $this->userService->register($request);

        if (!$result['success']) {
            return new JsonResponse($result, 400);
        }

        return new JsonResponse($result, 201);
    }
}