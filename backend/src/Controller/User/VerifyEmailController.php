<?php

namespace App\Controller\User;

use App\Service\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class VerifyEmailController extends AbstractController
{
    public function __construct(private UserService $userService)
    {}

    #[Route('/verify/email', name: 'app_verify_email')]
    public function __invoke(Request $request)
    {
        $token = $request->query->get('token');
        $id = $request->query->get('id');
        if (!$token || !$id) {
            return $this->json(['error' => 'Invalid verification link'], 400);
        }
        $result = $this->userService->verifyEmail($token, (int)$id);
        if (isset($result['error'])) {
            return $this->json($result, 400);
        }
        return new RedirectResponse('http://localhost:3000/login');
    }
}