<?php

namespace App\Service;

use App\Service\ImageService;
use App\Entity\ShippingMethod;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ShippingMethodRepository;
use Symfony\Component\HttpFoundation\Request;

class ShippingMethodService
{
    public function __construct(
        private readonly ShippingMethodRepository $shippingMethodRepository,
        private readonly ImageService $imageService,
        private readonly EntityManagerInterface $entityManager
    ){}
    
    public function createShippingMethod(Request $request)
    {
        $datas = $request->request->all();
        $file = $request->files->get("file");

        $shippingMethod = new ShippingMethod();
        $shippingMethod->setName($datas['name']);
        $shippingMethod->setPrice($datas['price']);
        if(isset($datas['description'])) $shippingMethod->setDescription($datas['description']);

        if($file) {
            $image = $this->imageService->uploadImage($file);
            $shippingMethod->setImage($image);
        }

        $this->shippingMethodRepository->save($shippingMethod, true);

        return $shippingMethod;
    }

    public function updateShippingMethod(Request $request)
    {
        $id = $request->attributes->get('id');
        $shippingMethod = $this->shippingMethodRepository->find($id);

        if(!$shippingMethod) throw new \Exception('ShippingMethod not found');

        $datas = $request->request->all();
        $file = $request->files->get("file");

        $shippingMethod->setName($datas['name']);
        $shippingMethod->setPrice($datas['price']);
        if(isset($datas['description'])) $shippingMethod->setDescription($datas['description']);

        $oldImage = $shippingMethod->getImage();

        if ($file) {
            if ($oldImage) {
                $this->imageService->deleteImageFile($oldImage);
            }
            $image = $this->imageService->uploadImage($file);
            $shippingMethod->setImage($image);
        } elseif (isset($datas["removeImage"])) {
            $this->imageService->deleteImageFile($oldImage);
            $shippingMethod->setImage(null);
        }

        $this->shippingMethodRepository->save($shippingMethod, true);
        return $shippingMethod;
    }

    public function deleteShippingMethod(int $id)
    {
        $shippingMethod = $this->shippingMethodRepository->find($id);
        if(!$shippingMethod) throw new \Exception('ShippingMethod not found');
        $image = $shippingMethod->getImage();
        if($image) $this->imageService->deleteImageFile($image);
        $this->entityManager->remove($shippingMethod);
        $this->entityManager->flush();
    }
}