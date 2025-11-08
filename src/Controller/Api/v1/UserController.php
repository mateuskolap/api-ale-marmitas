<?php

namespace App\Controller\Api\v1;

use App\Dto\Input\PaginationOptions;
use App\Dto\Input\User\UserCreateInput;
use App\Dto\Input\User\UserFilterInput;
use App\Dto\Input\User\UserUpdateInput;
use App\Dto\Output\User\UserOutput;
use App\Entity\User;
use App\Enum\Role;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(Role::ADMIN->value)]
#[Route('/api/v1/users', name: 'app_api_v1_users_')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
        private readonly ObjectMapperInterface $mapper,
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(
        #[MapQueryString] PaginationOptions $pagination,
        #[MapQueryString] UserFilterInput $filters,
    ): JsonResponse
    {
        return $this->json($this->userService->findAllPaginated($pagination, $filters));
    }

    #[Route('/{user}', name: 'show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        return $this->json($this->mapper->map($user, UserOutput::class));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] UserCreateInput $input): JsonResponse
    {
        return $this->json($this->userService->create($input), Response::HTTP_CREATED);
    }

    #[Route('/{user}', name: 'update', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] UserUpdateInput $input, User $user): JsonResponse
    {
        return $this->json($this->userService->update($input, $user));
    }

    #[Route('/{user}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user): JsonResponse
    {
        $this->userService->delete($user);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
