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

    public function findAllByUserWithSubscription(int $userId): array
    {
        return $this->createQueryBuilder('p')
            ->select(
                'p.id AS id',
                'p.name AS name',
                'p.description AS description',
                'p.price AS price',
                'p.stock AS stock',
                'p.is_active AS isActive',
                "CASE WHEN s.id IS NOT NULL THEN true ELSE false END AS subscribed"
            )
            ->leftJoin('App\Entity\Subscription', 's', 'WITH', 's.product = p AND s.user = :userId')
            ->where('p.is_active = true')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getArrayResult();
    }
}
