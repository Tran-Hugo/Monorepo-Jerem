<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\AddressRepository;

class AddressService
{
    public function __construct(
        private readonly AddressRepository $addressRepository
    ){}

    public function getAddressesByUser(User $user)
    {
        return $this->addressRepository->findBy(['user' => $user]);
    }
}