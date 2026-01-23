<?php

namespace App\Command;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


#[AsCommand(
    name: 'app:fix-slugs',
    description: 'Génère ou régénère les slugs pour tous les produits.'
)]
class FixSlugsCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private SluggerInterface $slugger
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo = $this->em->getRepository(Product::class);
        $products = $repo->findAll();

        foreach ($products as $product) {
            $slug = strtolower($this->slugger->slug($product->getTitle()));
            $product->setSlug($slug);
        }

        $categoryRepo = $this->em->getRepository(Category::class);
        $categories = $categoryRepo->findAll();

        foreach ($categories as $category) {
            $catSlug = strtolower($this->slugger->slug($category->getName()));
            $category->setSlug($catSlug);
        }

        $this->em->flush();

        $output->writeln('<info>Slugs générés avec succès.</info>');

        return Command::SUCCESS;
    }

}