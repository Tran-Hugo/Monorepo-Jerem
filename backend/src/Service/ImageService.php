<?php

namespace App\Service;

use App\Entity\Image;
use App\Repository\ImageRepository;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageService
{
    public function __construct(
        private readonly ImageRepository $imageRepository
    ) {}

    public function uploadImage(UploadedFile $file): Image
    {
        // 1. Vérifier que l'upload est valide
        if (!$file->isValid()) {
            throw new \RuntimeException('Invalid file upload.');
        }

        // 2. Vérifier le type MIME
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($file->getMimeType(), $allowedMimeTypes, true)) {
            throw new \RuntimeException('Invalid file type.');
        }

        // 3. Définir le dossier de destination
        $uploadDir = __DIR__ . '/../../public/uploads/images';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // 4. Générer un nom unique
        $fileName = uniqid('', true) . '.' . $file->guessExtension();

        // 5. Déplacer le fichier
        $file->move($uploadDir, $fileName);

        // 6. Créer l'entité Image et renseigner les champs
        $image = new Image();
        $image->setPath('uploads/images/' . $fileName);
        $image->setAltText(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        // 7. Retourner l'entité
        return $image;
    }

    public function deleteImageFile(Image $image): void
    {
        $filePath = __DIR__ . '/../../public/' . $image->getPath();
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    public function setMainImage(Image $image): void
    {
        $image->setMain(true);
        $product = $image->getProduct();
        if ($product) {
            foreach ($product->getImages() as $img) {
                if ($img !== $image) {
                    $img->setMain(false);
                    $this->imageRepository->save($img, true);
                }
            }
        }

        $this->imageRepository->save($image, true);
    }

}
