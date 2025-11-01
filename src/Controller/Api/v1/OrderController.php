<?php

namespace App\Controller\Api\v1;

use App\DTO\Input\Order\OrderCreateInput;
use App\DTO\Input\Order\OrderFilterInput;
use App\DTO\Input\PaginationOptions;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/orders', name: 'app_api_v1_orders_')]
final class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(
        #[MapQueryString] PaginationOptions $pagination,
        #[MapQueryString] OrderFilterInput $filters,
    ): JsonResponse
    {
        return $this->json($this->orderService->findAllPaginated($pagination, $filters));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] OrderCreateInput $input): JsonResponse
    {
        return $this->json($this->orderService->create($input), Response::HTTP_CREATED);
    }
}
