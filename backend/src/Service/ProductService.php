<?php

namespace App\Service;

use App\Entity\Order;
use DateTimeImmutable;
use App\Entity\Product;
use App\Service\ImageService;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;

class ProductService
{
    public function __construct(
        private ImageService $imageService,
        private ProductRepository $productRepository,
        private CategoryRepository $categoryRepository
        ){}

    public function createProduct(Request $request)
    {
        $datas = $request->request->all();
        $files = $request->files->get("files");

        $product = new Product();
        $product->setTitle($datas['title']);
        $product->setDescription($datas['description'] ?? null);
        $product->setPrice($datas['price']);
        $product->setVisible($datas['visible']);
        $product->setStock($datas['stock']);
        if (array_key_exists('mosaicPosition', $datas)) {
            $mosaicPosition = ($datas['mosaicPosition'] !== '' && $datas['mosaicPosition'] !== null) ? (int) $datas['mosaicPosition'] : null;
            if ($mosaicPosition !== null) {
                $conflict = $this->productRepository->findOneBy(['mosaicPosition' => $mosaicPosition, 'deleted' => false]);
                if ($conflict) {
                    $conflict->setMosaicPosition(null);
                }
            }
            $product->setMosaicPosition($mosaicPosition);
        }

        // Gestion des catégories
        $categoriesIds = $datas['categoriesIds'] ?? "[]";
        $categoriesIds = json_decode($categoriesIds, true);
        
        foreach ($categoriesIds as $categoryId) {
            $category = $this->categoryRepository->find($categoryId);
            if ($category) {
                $product->addCategory($category);
            }
        }

        // Gestion des fichiers et de l'image principale
        $uploadedImages = [];
        if ($files) {
            foreach ($files as $file) {
                $image = $this->imageService->uploadImage($file);
                $product->addImage($image);
                $uploadedImages[] = $image;
            }

            // Définir la mainImage si index fourni
            if (isset($datas['mainImageIndex'])) {
                $mainIndex = (int) $datas['mainImageIndex'];
                if (isset($uploadedImages[$mainIndex])) {
                    $this->imageService->setMainImage($uploadedImages[$mainIndex]);
                }
            } elseif (!empty($uploadedImages)) {
                // Par défaut, la première image devient la mainImage
                $this->imageService->setMainImage($uploadedImages[0]);
            }
        }

        $this->productRepository->save($product, true);

        return $product;
    }


    public function updateProduct(Request $request)
    {
        $id = $request->attributes->get('id');
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new \Exception('Product not found');
        }

        $datas = $request->request->all();
        $files = $request->files->get("files");

        // === Champs simples ===
        $product->setTitle($datas['title']);
        $product->setDescription($datas['description'] ?? null);
        $product->setPrice($datas['price']);
        $product->setVisible($datas['visible']);
        $product->setStock($datas['stock']);
        if (array_key_exists('mosaicPosition', $datas)) {
            $mosaicPosition = ($datas['mosaicPosition'] !== '' && $datas['mosaicPosition'] !== null) ? (int) $datas['mosaicPosition'] : null;
            if ($mosaicPosition !== null) {
                $conflict = $this->productRepository->findOneBy(['mosaicPosition' => $mosaicPosition, 'deleted' => false]);
                if ($conflict && $conflict->getId() !== $product->getId()) {
                    $conflict->setMosaicPosition(null);
                }
            }
            $product->setMosaicPosition($mosaicPosition);
        }

        // === Gestion des catégories ===
        $categoriesIds = $datas['categoriesIds'] ?? '[]';
        $categoriesIds = is_string($categoriesIds) ? json_decode($categoriesIds, true) : $categoriesIds;

        if (!is_array($categoriesIds)) {
            throw new \InvalidArgumentException('categoriesIds must be an array');
        }

        $alreadyCategories = $product->getCategories()->toArray();
        $alreadyCategoryIds = array_map(fn($c) => $c->getId(), $alreadyCategories);

        foreach ($categoriesIds as $categoryId) {
            if (!in_array($categoryId, $alreadyCategoryIds, true)) {
                $category = $this->categoryRepository->find($categoryId);
                if (!$category) {
                    throw new \Exception("Category not found: $categoryId");
                }
                $product->addCategory($category);
            }
        }

        foreach ($alreadyCategories as $category) {
            if (!in_array($category->getId(), $categoriesIds, true)) {
                $product->removeCategory($category);
            }
        }

        // === Gestion des images ===
        $existingImageIds = $datas['existingImages'] ?? '[]';
        $existingImageIds = is_string($existingImageIds) ? json_decode($existingImageIds, true) : $existingImageIds;

        foreach ($product->getImages() as $image) {
            if (!in_array($image->getId(), $existingImageIds, true)) {
                $this->imageService->deleteImageFile($image);
                $product->removeImage($image);
            }
        }

        $uploadedImages = [];
        if ($files) {
            foreach ($files as $file) {
                $image = $this->imageService->uploadImage($file);
                $product->addImage($image);
                $uploadedImages[] = $image;
            }
        }

        // === Gestion de la mainImage ===
        $mainImageId = $datas['mainImageId'] ?? null;

        if ($mainImageId) {
            // Cherche dans les images existantes
            $mainImage = $product->getImages()->filter(fn($img) => $img->getId() == $mainImageId)->first();
            if (!$mainImage && !empty($uploadedImages)) {
                preg_match('/\d+/', $mainImageId, $matches);
                $numero = $matches[0];
                $mainImage = $uploadedImages[$numero];
            }
            if ($mainImage) {
                $this->imageService->setMainImage($mainImage);
            }
        } elseif (!empty($product->getImages())) {
            // Par défaut, la première image devient la mainImage
            $this->imageService->setMainImage($product->getImages()->first());
        }

        $this->productRepository->save($product, true);

        return $product;
    }



    public function deleteProduct(int $id): void
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new \Exception('Product not found');
        }

        $product->setDeleted(true);

        $this->productRepository->save($product, true);
    }

    public function decreaseStock(Order $order): void
    {
        foreach ($order->getOrderItems() as $item) {
            $product = $item->getProduct();
            $newQuantity = $product->getStock() - $item->getQuantity();
            $product->setStock(max(0, $newQuantity));
        }
    }


}