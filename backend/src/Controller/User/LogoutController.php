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
        $cookieDomain = $_ENV['COOKIE_DOMAIN'] ?? null;
        $cookieDomain = ($cookieDomain !== '' && $cookieDomain !== null) ? $cookieDomain : null;

        $response = new JsonResponse(['message' => 'Logged out successfully']);
        $response->headers->clearCookie(
            'BEARER',         // nom du cookie
            '/',              // path
            $cookieDomain,    // domain
            true,             // secure si ton site est en HTTPS
            true,             // httponly
            'none'            // SameSite
        );

        return $response;
    }
}