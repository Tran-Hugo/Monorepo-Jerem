<?php

namespace App\Controller\Product;
use App\Service\ProductService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[AsController]
class DeleteProductController
{
    public function __construct(private readonly ProductService $productService) {}

    #[IsGranted('ROLE_ADMIN')]
    public function __invoke(Request $request): Response
    {
        $id = $request->attributes->get('id');
        $this->productService->deleteProduct($id);
        return new JsonResponse(['status' => 'Product deleted successfully'], 200);
    }
}