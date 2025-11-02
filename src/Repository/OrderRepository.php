<?php

namespace App\Repository;

use App\DTO\Input\Order\OrderFilterInput;
use App\Entity\Order;
use App\Repository\CustomerRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CustomerRepository $customerRepository,
        ManagerRegistry                         $registry,
    )
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * Find all order query
     *
     * @param OrderFilterInput|null $filters
     * @return Query
     */
    public function findFilteredQuery(?OrderFilterInput $filters = null): Query
    {
        $qb = $this->createQueryBuilder('o');

        $filters->customerId && $qb->andWhere('o.customer = :customer')
            ->setParameter('customer', $this->customerRepository->find($filters->customerId));
        $filters->status && $qb->andWhere('o.status = :status')->setParameter('status', $filters->status);
        $filters->createdFrom && $qb->andWhere('o.createdAt >= :createdFrom')->setParameter('createdFrom', $filters->createdFrom);
        $filters->createdTo && $qb->andWhere('o.createdAt <= :createdTo')->setParameter('createdTo', $filters->createdTo);

        return $qb->getQuery();
    }

    /**
     * Save an order entity
     *
     * @param Order $order
     * @param bool $flush
     * @return void
     */
    public function save(Order $order, bool $flush = false): void
    {
        $this->em->persist($order);

        if ($flush) {
            $this->em->flush();
        }
    }
}
