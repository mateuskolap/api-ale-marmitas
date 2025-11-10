<?php

namespace App\Repository;

use App\Dto\Input\Product\ProductFilterInput;
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
     * @param ProductFilterInput|null $filters
     * @return Query
     */
    public function findFilteredQuery(?ProductFilterInput $filters = null): Query
    {
        $qb = $this->createQueryBuilder('p');

        $filters->name && $qb->andWhere('p.name LIKE :name')->setParameter('name', '%' . $filters->name . '%');
        $filters->category && $qb->andWhere('p.category = :category')->setParameter('category', $filters->category);

        return $qb->getQuery();
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
