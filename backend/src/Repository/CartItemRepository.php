<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\CartItem;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<CartItem>
 */
class CartItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartItem::class);
    }

    public function save(CartItem $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getTotalQuantity(User $user): int
    {
        return (int) $this->createQueryBuilder('c')
            ->select('SUM(c.quantity)')
            ->where('c.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function remove(CartItem $cartItem, bool $flush = false): void
    {
        $this->getEntityManager()->remove($cartItem);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function clearCart(User $user): void
    {
        $qb = $this->createQueryBuilder('c');
        
        $qb->delete()
        ->where('c.user = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->execute();
    }


    //    /**
    //     * @return CartItem[] Returns an array of CartItem objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?CartItem
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
