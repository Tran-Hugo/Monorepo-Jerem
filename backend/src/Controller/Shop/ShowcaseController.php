<?php

namespace App\Controller\Shop;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class ShowcaseController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    #[Route('/api/stats/showcase', name: 'showcase_shop', methods: ['GET'])]
    public function __invoke(): JsonResponse
    {
        $mosaic = $this->productRepository->findMosaicProducts();
        $latest = $this->productRepository->findBy(["deleted" => false, "visible" => true], ['id' => 'DESC'], 5);
        $art = $this->productRepository->findByCategoryName('Art', 5);
        $photo = $this->productRepository->findByCategoryName('Photo', 5);

        return $this->json([
            'mosaic' => $mosaic,
            'latest' => $latest,
            'art' => $art,
            'photo' => $photo,
        ], 200, [], ['groups' => ['product:read']]);
    }
}
