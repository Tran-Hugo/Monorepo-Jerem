<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCategoryName(string $name, int $limit = 5): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.categories', 'c')
            ->andWhere('c.name = :name')
            ->andWhere('p.deleted = false')
            ->andWhere('p.visible = true')
            ->setParameter('name', $name)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function searchByName(
        ?string $query,
        ?string $orderBy = null,
        ?string $categorySlug = null,
        int $page = 1,
        int $limit = 20
    ): array {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.deleted = false')
            ->andWhere('p.visible = true');

        if ($query !== null) {
            $qb->andWhere('p.title LIKE :query')
                ->setParameter('query', '%' . $query . '%');

        }
        // Tri optionnel
        if ($orderBy !== null) {
            $orderBy = strtoupper($orderBy);
            if (in_array($orderBy, ['ASC', 'DESC'], true)) {
                $qb->orderBy('p.id', $orderBy);
            }
        }

        // Filtrage par catégorie optionnel
        if ($categorySlug !== null) {
            $qb->join('p.categories', 'c')
               ->andWhere('c.slug = :categorySlug')
               ->setParameter('categorySlug', $categorySlug);
        }

        // Pagination
        $offset = ($page - 1) * $limit;

        $qb->setFirstResult($offset)
        ->setMaxResults($limit);

        return $qb->getQuery()->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
