<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        ManagerRegistry                         $registry
    )
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Find all users query
     *
     * @return Query
     */
    public function findAllQuery(): Query
    {
        return $this->createQueryBuilder('u')
            ->getQuery();
    }

    /**
     * Save a user entity
     *
     * @param User $entity
     * @param bool $flush
     * @return void
     */
    public function save(User $entity, bool $flush = false): void
    {
        $this->em->persist($entity);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Delete a user entity
     *
     * @param User $user
     * @param bool $flush
     * @return void
     */
    public function delete(User $user, bool $flush = false): void
    {
        $this->em->remove($user);

        if ($flush) {
            $this->em->flush();
        }
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->em->persist($user);
        $this->em->flush();
    }
}
