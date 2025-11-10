<?php

namespace App\Repository;

use App\Dto\Input\Payment\PaymentFilterInput;
use App\Entity\Payment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Payment>
 */
class PaymentRepository extends ServiceEntityRepository
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        ManagerRegistry                         $registry,
    )
    {
        parent::__construct($registry, Payment::class);
    }

    /**
     * Find all payment query
     *
     * @param PaymentFilterInput|null $filters
     * @return Query
     */
    public function findFilteredQuery(?PaymentFilterInput $filters = null): Query
    {
        $qb = $this->createQueryBuilder('p');

        $filters->customerId && $qb->andWhere('p.customer = :customerId')->setParameter('customerId', $filters->customerId);
        $filters->dateFrom && $qb->andWhere('p.date >= :dateFrom')->setParameter('dateFrom', $filters->dateFrom);
        $filters->dateTo && $qb->andWhere('p.date <= :dateTo')->setParameter('dateTo', $filters->dateTo);
        $filters->method && $qb->andWhere('p.method = :method')->setParameter('method', $filters->method);

        return $qb->getQuery();
    }

    /**
     * Save a payment entity
     *
     * @param Payment $payment
     * @param bool $flush
     * @return void
     */
    public function save(Payment $payment, bool $flush = false): void
    {
        $this->em->persist($payment);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Delete a payment entity
     *
     * @param Payment $payment
     * @param bool $flush
     * @return void
     */
    public function remove(Payment $payment, bool $flush = false): void
    {
        $this->em->remove($payment);

        if ($flush) {
            $this->em->flush();
        }
    }
}
