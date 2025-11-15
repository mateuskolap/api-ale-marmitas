<?php

namespace App\Service;

use App\Dto\Input\User\UserCreateInput;
use App\Dto\Input\User\UserFilterInput;
use App\Dto\Input\User\UserUpdateInput;
use App\Dto\Output\Pagination\PaginatedList;
use App\Dto\Output\User\UserOutput;
use App\Entity\User;
use App\Exception\EmailAlreadyExistsException;
use App\Exception\UserNotFoundException;
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
     * @param int $id
     * @return UserOutput
     */
    public function show(int $id): UserOutput
    {
        $user = $this->userRepository->find($id);
        if (!$user) {
            throw new UserNotFoundException($id);
        }

        return $this->mapper->map($user, UserOutput::class);
    }

    /**
     * Find all users with pagination
     *
     * @param UserFilterInput $filters
     * @return PaginatedList
     */
    public function findAllPaginated(UserFilterInput $filters): PaginatedList
    {
        $paginatedResults = $this->paginator->paginate(
            $this->userRepository->findFilteredQuery($filters),
            $filters->page,
            $filters->size
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
     * @param int $userId
     * @return UserOutput
     */
    public function update(UserUpdateInput $input, int $userId): UserOutput
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new UserNotFoundException($userId);
        }

        if ($input->email) {
            $existingUser = $this->userRepository->findOneBy(['email' => $input->email]);
            if ($existingUser && $existingUser->getId() !== $user->getId()) {
                throw new EmailAlreadyExistsException($input->email);
            }
            $user->setEmail($input->email);
        }

        $input->roles && $user->setRoles($input->roles);
        $input->password && $user->setPassword($this->hasher->hashPassword($user, $input->password));

        $this->userRepository->save($user, true);

        return $this->mapper->map($user, UserOutput::class);
    }

    /**
     * Delete a user
     *
     * @param int $userId
     */
    public function delete(int $userId): void
    {
        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new UserNotFoundException($userId);
        }

        $this->userRepository->delete($user, true);
    }
}
