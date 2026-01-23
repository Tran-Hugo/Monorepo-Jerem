<?php

namespace App\Entity;

use App\Entity\Country;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AddressRepository;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: AddressRepository::class)]
class Address
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?string $lastname = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?string $company = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?string $city = null;

    #[ORM\Column(length: 10)]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?string $postalCode = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?Country $country = null;

    #[ORM\Column(length: 15, nullable: true)]
    #[Groups(['address:read','orders:read', 'order:read'])]
    private ?string $phone = null;

    #[ORM\ManyToOne(inversedBy: 'addresses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['address:read'])]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): static
    {
        $this->street = $street;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postalCode;
    }

    public function setPostalCode(string $postalCode): static
    {
        $this->postalCode = $postalCode;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }
}
