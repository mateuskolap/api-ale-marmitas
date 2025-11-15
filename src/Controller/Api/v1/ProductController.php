<?php

namespace App\Controller\Api\v1;

use App\Dto\Input\Product\ProductCreateInput;
use App\Dto\Input\Product\ProductFilterInput;
use App\Dto\Input\Product\ProductUpdateInput;
use App\Enum\Role;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(Role::MANAGER->value)]
#[Route('/api/v1/products', name: 'app_api_v1_products_')]
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService,
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(#[MapQueryString] ProductFilterInput $filters): JsonResponse
    {
        return $this->json($this->productService->findAllPaginated($filters));
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->productService->show($id));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] ProductCreateInput $input): JsonResponse
    {
        return $this->json($this->productService->create($input), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] ProductUpdateInput $input, int $id): JsonResponse
    {
        return $this->json($this->productService->update($input, $id));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->productService->delete($id);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
