<?php

namespace App\Controller\Api\v1;

use App\Dto\Input\User\UserCreateInput;
use App\Dto\Input\User\UserFilterInput;
use App\Dto\Input\User\UserUpdateInput;
use App\Enum\Role;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(Role::ADMIN->value)]
#[Route('/api/v1/users', name: 'app_api_v1_users_')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(#[MapQueryString] UserFilterInput $filters): JsonResponse
    {
        return $this->json($this->userService->findAllPaginated($filters));
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->userService->show($id));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] UserCreateInput $input): JsonResponse
    {
        return $this->json($this->userService->create($input), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] UserUpdateInput $input, int $id): JsonResponse
    {
        return $this->json($this->userService->update($input, $id));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->userService->delete($id);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
