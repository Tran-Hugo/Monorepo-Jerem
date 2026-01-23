<?php

namespace App\EventListener;

use App\Entity\Product;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener
{
    public function __construct(private SluggerInterface $slugger) {}

    public function prePersist(Product $product, PrePersistEventArgs $event): void
    {
        $this->generateSlug($product);
    }

    public function preUpdate(Product $product, PreUpdateEventArgs $event): void
    {
        // Regénérer le slug uniquement si le titre change
        if ($event->hasChangedField('title')) {
            $this->generateSlug($product);
        }
    }

    private function generateSlug(Product $product): void
    {
        if (!$product->getTitle()) {
            return;
        }

        $slug = strtolower($this->slugger->slug($product->getTitle()));
        $product->setSlug($slug);
    }
}