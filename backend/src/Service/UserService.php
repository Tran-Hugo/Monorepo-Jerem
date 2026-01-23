<?php

namespace App\Service;

use App\Entity\User;
use App\Service\EmailService;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private ValidatorInterface $validator,
        private EmailService $emailService
    ){}

    public function register(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPassword($data['password']);
        $user->setFirstname($data['firstname']);
        $user->setLastname($data['lastname']);
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(false);
        $user->setVerificationToken(bin2hex(random_bytes(32)));

        $errors = $this->validator->validate($user);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }

            return [
                'success' => false,
                'errors' => $errorMessages
            ];
        }
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user, true);
        
        $this->emailService->sendVerificationEmail($user->getEmail(), $user, $user->getVerificationToken());

        return [
            'success' => true
        ];
    }

    public function verifyEmail(string $token, int $id)
    {
        $user = $this->userRepository->find($id);
        if (!$user || $user->getVerificationToken() !== $token) {
            return [
                'success' => false,
                'message' => 'Token invalide ou utilisateur non trouvé.'
            ];
        }

        $user->setVerified(true);
        $user->setVerificationToken(null);
        $this->userRepository->save($user, true);

        return [
            'success' => true,
            'message' => 'Email vérifié avec succès.'
        ];
    }
}