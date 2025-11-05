<?php

namespace App\Controller\Api\v1;

use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/v1/payments', name: 'app_api_v1_payments_')]
final class PaymentController extends AbstractController
{
    public function __construct(
        private readonly PaymentService $paymentService,
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/{payment}', name: 'show', methods: ['GET'])]
    public function show(): JsonResponse
    {
        return $this->json([]);
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(): JsonResponse
    {
        return $this->json([], Response::HTTP_CREATED);
    }

    #[Route('/{payment}', name: 'update', methods: ['PATCH'])]
    public function update(): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/{payment}', name: 'delete', methods: ['DELETE'])]
    public function delete(): JsonResponse
    {
        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
