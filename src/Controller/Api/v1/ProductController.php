<?php

namespace App\Controller\Api\v1;

use App\DTO\Input\PaginationOptions;
use App\DTO\Input\Product\ProductCreateInput;
use App\DTO\Input\Product\ProductFilterInput;
use App\DTO\Input\Product\ProductUpdateInput;
use App\Entity\Product;
use App\Enum\Role;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(Role::ADMIN->value)]
#[Route("/api/v1/products", name: "app_api_v1_products_")]
final class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService,
    )
    {
    }

    #[Route(name: "list", methods: ["GET"])]
    public function list(
        #[MapQueryString] PaginationOptions $options,
        #[MapQueryString] ProductFilterInput $filters,
    ): JsonResponse
    {
        return $this->json($this->productService->findAllPaginated($options, $filters));
    }

    #[Route(name: "create", methods: ["POST"])]
    public function create(#[MapRequestPayload] ProductCreateInput $input): JsonResponse
    {
        return $this->json($this->productService->create($input), Response::HTTP_CREATED);
    }

    #[Route('/{product}', name: "update", methods: ["PATCH"])]
    public function update(#[MapRequestPayload] ProductUpdateInput $input, Product $product): JsonResponse
    {
        return $this->json($this->productService->update($input, $product));
    }

    #[Route('/{product}', name: "delete", methods: ["DELETE"])]
    public function delete(Product $product): JsonResponse
    {
        $this->productService->delete($product);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
