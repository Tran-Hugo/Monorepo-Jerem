<?php

namespace App\Controller\User;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class LogoutController
{
    public function __invoke(): JsonResponse
    {
        $response = new JsonResponse(['message' => 'Logged out successfully']);
        $response->headers->clearCookie(
            'BEARER',         // nom du cookie
            '/',              // path
            null,             // domain (null = domaine courant)
            true,             // secure si ton site est en HTTPS
            true,             // httponly
            'none'             // SameSite (peut être 'lax', 'strict' ou 'none') /!\ penser à mettre lax en prod
        );

        return $response;
    }
}