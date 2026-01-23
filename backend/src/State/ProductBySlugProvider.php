<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductBySlugProvider implements ProviderInterface
{
    public function __construct(private EntityManagerInterface $em) {}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): Product
    {
        $slug = $uriVariables['slug'] ?? null;

        $product = $this->em->getRepository(Product::class)
            ->findOneBy(['slug' => $slug]);

        if (!$product) {
            throw new NotFoundHttpException("Produit introuvable pour le slug : $slug");
        }

        return $product;
    }
}
