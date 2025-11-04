<?php

namespace App\Service;

use App\DTO\Input\PaginationOptions;
use App\DTO\Input\Product\ProductFilterInput;
use App\DTO\Input\User\UserCreateInput;
use App\DTO\Input\User\UserFilterInput;
use App\DTO\Input\User\UserUpdateInput;
use App\DTO\Output\PaginatedList;
use App\DTO\Output\User\UserOutput;
use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

readonly class UserService
{
    public function __construct(
        private UserRepository              $userRepository,
        private PaginatorInterface          $paginator,
        private UserPasswordHasherInterface $hasher,

    )
    {
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
        $pagination = $this->paginator->paginate(
            $this->userRepository->findFilteredQuery($filters),
            $pagination->page,
            $pagination->size
        );

        $pagination->setItems(array_map(
            fn(User $user) => new UserOutput($user),
            $pagination->getItems()
        ));

        return new PaginatedList($pagination);
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
            && throw new \InvalidArgumentException('User with this email already exists.', Response::HTTP_BAD_REQUEST);

        $user = (new User())
            ->setEmail($input->email)
            ->setRoles($input->roles);
        $user->setPassword($this->hasher->hashPassword($user, $input->password));

        $this->userRepository->save($user, true);

        return new UserOutput($user);
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

        return new UserOutput($user);
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
