<?php

namespace App\Controller\Address;

use App\Service\AddressService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class MyAddressesController
{
    public function __construct(
        private readonly Security $security,
        private readonly AddressService $addressService,
        private readonly SerializerInterface $serializer
    ) {}

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function __invoke()
    {
        $user = $this->security->getUser();
        if (!$user) {
            return new JsonResponse(['error' => 'Unauthorized'], 401);
        }
        $addresses = $this->addressService->getAddressesByUser($user);
        $json = $this->serializer->serialize($addresses, 'json', ['groups' => ['address:read']]);
        return new JsonResponse($json, 200, [], true);
    }
}