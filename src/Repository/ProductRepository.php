<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        ManagerRegistry                         $registry,
    )
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Find all product query
     *
     * @return Query
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('p')
            ->getQuery();
    }

    /**
     * Save a product entity
     *
     * @param Product $entity
     * @param bool $flush
     * @return void
     */
    public function save(Product $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Delete a product entity
     *
     * @param Product $product
     * @param bool $flush
     * @return void
     */
    public function delete(Product $product, bool $flush = false): void
    {
        $this->em->remove($product);

        if ($flush) {
            $this->em->flush();
        }
    }
}
