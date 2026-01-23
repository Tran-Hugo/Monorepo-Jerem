<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isVerified()) {
            throw new CustomUserMessageAuthenticationException(
                "Votre compte n'a pas encore été vérifié. Merci de consulter vos e-mails pour finaliser l'activation."
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // rien de spécial ici pour JWT
    }
}
