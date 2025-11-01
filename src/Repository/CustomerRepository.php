<?php

namespace App\Repository;

use App\DTO\Input\Customer\CustomerFilterInput;
use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(
        ManagerRegistry $registry,
        private readonly EntityManagerInterface $em,
    )
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * Find all customer query
     *
     * @param CustomerFilterInput|null $filters
     * @return Query
     */
    public function findFilteredQuery(?CustomerFilterInput $filters): Query
    {
        $qb = $this->createQueryBuilder('c');

        $filters->name && $qb->andWhere('c.name LIKE :name')->setParameter('name', '%' . $filters->name . '%');
        $filters->emailOrPhone && $qb->andWhere('c.email LIKE :contact OR c.phone LIKE :contact')
            ->setParameter('contact', '%' . $filters->emailOrPhone . '%');

        return $qb->getQuery();
    }

    /**
     * Save a customer entity
     *
     * @param Customer $entity
     * @param bool $flush
     * @return void
     */
    public function save(Customer $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Delete a customer entity
     *
     * @param Customer $customer
     * @param bool $flush
     * @return void
     */
    public function delete(Customer $customer, bool $flush = false): void
    {
        $this->em->remove($customer);

        if ($flush) {
            $this->em->flush();
        }
    }
}
