<?php

namespace App\Controller\Product;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class SearchProductController extends AbstractController
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    #[Route('/api/search', name: 'search', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $query = $request->query->get('q');
        $orderBy = $request->query->get('sort');
        $category = $request->query->get('category');
        $page = (int) $request->query->get('page', 1);
        $limit = (int) $request->query->get('limit', 20);

        $products = $this->productRepository->searchByName($query, $orderBy, $category, $page, $limit);
        return $this->json($products);
    }
}
