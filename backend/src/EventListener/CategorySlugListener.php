<?php

namespace App\EventListener;

use App\Entity\Category;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugListener
{
    public function __construct(private SluggerInterface $slugger) {}

    public function prePersist(Category $category, PrePersistEventArgs $event): void
    {
        $this->generateSlug($category);
    }

    public function preUpdate(Category $category, PreUpdateEventArgs $event): void
    {
        // Regénérer le slug uniquement si le nom change
        if ($event->hasChangedField('name')) {
            $this->generateSlug($category);
        }
    }

    private function generateSlug(Category $category): void
    {
        if (!$category->getName()) {
            return;
        }

        $slug = strtolower($this->slugger->slug($category->getName()));
        $category->setSlug($slug);
    }
}