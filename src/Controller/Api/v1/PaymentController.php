<?php

namespace App\Controller\Api\v1;

use App\Dto\Input\PaginationOptions;
use App\Dto\Input\Payment\PaymentCreateInput;
use App\Dto\Input\Payment\PaymentFilterInput;
use App\Dto\Input\Payment\PaymentUpdateInput;
use App\Entity\Payment;
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
    public function list(
        #[MapQueryString] PaginationOptions  $pagination,
        #[MapQueryString] PaymentFilterInput $filters,
    ): JsonResponse
    {
        return $this->json($this->paymentService->findAllPaginated($pagination, $filters));
    }

    #[Route('/{payment}', name: 'show', methods: ['GET'])]
    public function show(Payment $payment): JsonResponse
    {
        return $this->json($this->paymentService->show($payment));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] PaymentCreateInput $input): JsonResponse
    {
        return $this->json($this->paymentService->create($input), Response::HTTP_CREATED);
    }

    #[Route('/{payment}', name: 'update', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] PaymentUpdateInput $input, Payment $payment): JsonResponse
    {
        return $this->json($this->paymentService->update($input, $payment));
    }

    #[Route('/{payment}', name: 'delete', methods: ['DELETE'])]
    public function delete(Payment $payment): JsonResponse
    {
        $this->paymentService->delete($payment);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
