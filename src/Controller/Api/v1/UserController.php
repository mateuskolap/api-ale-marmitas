<?php

namespace App\Controller\Api\v1;

use App\DTO\Input\PaginationOptions;
use App\DTO\Input\User\UserCreateInput;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted("ROLE_ADMIN")]
#[Route('/api/v1/users', name: 'app_api_v1_users_')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserService $userService,
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(#[MapQueryString] PaginationOptions $options): JsonResponse
    {
        return $this->json($this->userService->findAllPaginated($options));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] UserCreateInput $input): JsonResponse
    {
        return $this->json($this->userService->create($input));
    }

    #[Route('/{user}', name: 'update', methods: ['PATCH'])]
    public function update(): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/{user}', name: 'delete', methods: ['DELETE'])]
    public function delete(): JsonResponse
    {
        return $this->json([]);
    }
}
