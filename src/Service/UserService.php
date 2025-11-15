<?php

namespace App\Service;

use App\Dto\Input\PaginationOptions;
use App\Dto\Input\User\UserCreateInput;
use App\Dto\Input\User\UserFilterInput;
use App\Dto\Input\User\UserUpdateInput;
use App\Dto\Output\Pagination\PaginatedList;
use App\Dto\Output\User\UserOutput;
use App\Entity\User;
use App\Exception\EmailAlreadyExistsException;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserService
{
    public function __construct(
        private UserRepository              $userRepository,
        private PaginatorInterface          $paginator,
        private UserPasswordHasherInterface $hasher,
        private ObjectMapperInterface       $mapper,
    )
    {
    }

    /**
     * Show a single user
     *
     * @param User $user
     * @return UserOutput
     */
    public function show(User $user): UserOutput
    {
        return $this->mapper->map($user, UserOutput::class);
    }

    /**
     * Find all users with pagination
     *
     * @param PaginationOptions $pagination
     * @param UserFilterInput|null $filters
     * @return PaginatedList
     */
    public function findAllPaginated(PaginationOptions $pagination, ?UserFilterInput $filters = null): PaginatedList
    {
        $paginatedResults = $this->paginator->paginate(
            $this->userRepository->findFilteredQuery($filters),
            $pagination->page,
            $pagination->size
        );

        $paginatedResults->setItems(array_map(
            fn(User $user) => $this->mapper->map($user, UserOutput::class),
            $paginatedResults->getItems()
        ));

        return new PaginatedList($paginatedResults);
    }

    /**
     * Create a new user
     *
     * @param UserCreateInput $input
     * @return UserOutput
     */
    public function create(UserCreateInput $input): UserOutput
    {
        $this->userRepository->findOneBy(['email' => $input->email])
            && throw new EmailAlreadyExistsException($input->email);

        $user = (new User())
            ->setEmail($input->email)
            ->setRoles($input->roles);
        $user->setPassword($this->hasher->hashPassword($user, $input->password));

        $this->userRepository->save($user, true);

        return $this->mapper->map($user, UserOutput::class);
    }

    /**
     * Update an existing user
     *
     * @param UserUpdateInput $input
     * @param User $user
     * @return UserOutput
     */
    public function update(UserUpdateInput $input, User $user): UserOutput
    {
        $input->email && $user->setEmail($input->email);
        $input->roles && $user->setRoles($input->roles);
        $input->password && $user->setPassword($this->hasher->hashPassword($user, $input->password));

        $this->userRepository->save($user, true);

        return $this->mapper->map($user, UserOutput::class);
    }

    /**
     * Delete a user
     *
     * @param User $user
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user, true);
    }
}
