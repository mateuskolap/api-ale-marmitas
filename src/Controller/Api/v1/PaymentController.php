<?php

namespace App\Controller\Api\v1;

use App\Dto\Input\Payment\PaymentCreateInput;
use App\Dto\Input\Payment\PaymentFilterInput;
use App\Dto\Input\Payment\PaymentUpdateInput;
use App\Enum\Role;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(Role::MANAGER->value)]
#[Route('/api/v1/payments', name: 'app_api_v1_payments_')]
final class PaymentController extends AbstractController
{
    public function __construct(
        private readonly PaymentService $paymentService,
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(#[MapQueryString] PaymentFilterInput $filters): JsonResponse
    {
        return $this->json($this->paymentService->findAllPaginated($filters));
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->paymentService->show($id));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] PaymentCreateInput $input): JsonResponse
    {
        return $this->json($this->paymentService->create($input), Response::HTTP_CREATED);
    }

    #[Route('/{id}', name: 'update', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] PaymentUpdateInput $input, int $id): JsonResponse
    {
        return $this->json($this->paymentService->update($input, $id));
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $this->paymentService->delete($id);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
