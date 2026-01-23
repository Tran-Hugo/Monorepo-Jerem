<?php

namespace App\Controller\Product;

use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class CreateProductController
{
    public function __construct(private readonly ProductService $productService) {}

    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request): Response
    {
        $this->productService->createProduct($request);

        return new JsonResponse(['status' => 'Product created successfully'], 201);
    }
}