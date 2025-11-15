<?php

namespace App\Controller\Api\v1;

use App\Dto\Input\Customer\CustomerCreateInput;
use App\Dto\Input\Customer\CustomerFilterInput;
use App\Dto\Input\Customer\CustomerUpdateInput;
use App\Entity\Customer;
use App\Enum\Role;
use App\Service\CustomerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted(Role::MANAGER->value)]
#[Route('/api/v1/customers', name: 'app_api_v1_customers_')]
final class CustomerController extends AbstractController
{
    public function __construct(
        private readonly CustomerService $customerService,
    )
    {
    }

    #[Route(name: 'list', methods: ['GET'])]
    public function list(#[MapQueryString] CustomerFilterInput $filters): JsonResponse
    {
        return $this->json($this->customerService->findAllPaginated($filters));
    }

    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        return $this->json($this->customerService->show($id));
    }

    #[Route(name: 'create', methods: ['POST'])]
    public function create(#[MapRequestPayload] CustomerCreateInput $input): JsonResponse
    {
        return $this->json($this->customerService->create($input), Response::HTTP_CREATED);
    }

    #[Route('/{customer}', name: 'update', methods: ['PATCH'])]
    public function update(#[MapRequestPayload] CustomerUpdateInput $input, Customer $customer): JsonResponse
    {
        return $this->json($this->customerService->update($input, $customer));
    }

    #[Route('/{customer}', name: 'delete', methods: ['DELETE'])]
    public function delete(Customer $customer): JsonResponse
    {
        $this->customerService->delete($customer);

        return new JsonResponse(status: Response::HTTP_NO_CONTENT);
    }
}
