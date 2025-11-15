<?php

namespace App\Controller\Api\v1;

use App\Dto\Input\Order\OrderCreateInput;
use App\Dto\Input\Order\OrderFilterInput;
use App\Dto\Input\Order\OrderStatusUpdateInput;
use App\Dto\Input\PaginationOptions;
use App\Entity\Order;
use App\Enum\OrderStatus;
use App\Enum\Role;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(Role::MANAGER->value)]
#[Route('/api/v1/orders', name: 'app_api_v1_orders_')]
final class OrderController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(
        #[MapQueryString] PaginationOptions $pagination,
        #[MapQueryString] OrderFilterInput  $filters,
    ): JsonResponse
    {
        return $this->json($this->orderService->findAllPaginated($pagination, $filters));
    }

    #[Route('/{order}', name: 'show', methods: ['GET'])]
    public function show(Order $order): JsonResponse
    {
        return $this->json($this->orderService->show($order));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] OrderCreateInput $input): JsonResponse
    {
        return $this->json($this->orderService->create($input), Response::HTTP_CREATED);
    }

    #[Route('/{order}/status', name: 'update_status', methods: ['PATCH'])]
    public function updateStatus(Order $order, #[MapRequestPayload] OrderStatusUpdateInput $input): JsonResponse
    {
        return $this->json($this->orderService->updateStatus($order, OrderStatus::from($input->status)));
    }
}
