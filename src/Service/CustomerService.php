<?php

namespace App\Service;

use App\Dto\Input\Customer\CustomerCreateInput;
use App\Dto\Input\Customer\CustomerFilterInput;
use App\Dto\Input\Customer\CustomerUpdateInput;
use App\Dto\Output\Customer\CustomerOutput;
use App\Dto\Output\Pagination\PaginatedList;
use App\Entity\Customer;
use App\Exception\CustomerNotFoundException;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

readonly class CustomerService
{
    public function __construct(
        private PaginatorInterface $paginator,
        private CustomerRepository $customerRepository,
        private ObjectMapperInterface $mapper,
    )
    {
    }

    /**
     * Show a single customer
     *
     * @param int $id
     * @return CustomerOutput
     */
    public function show(int $id): CustomerOutput
    {
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            throw new CustomerNotFoundException($id);
        }

        return $this->mapper->map($customer, new CustomerOutput());
    }

    /**
     * Find all customers with pagination
     *
     * @param CustomerFilterInput|null $filters
     * @return PaginatedList
     */
    public function findAllPaginated(CustomerFilterInput $filters = null): PaginatedList
    {
        $paginatedResult = $this->paginator->paginate(
            $this->customerRepository->findFilteredQuery($filters),
            $filters->page,
            $filters->size
        );

        $paginatedResult->setItems(array_map(
            fn(Customer $customer) => $this->mapper->map($customer, CustomerOutput::class),
            $paginatedResult->getItems()
        ));

        return new PaginatedList($paginatedResult);
    }

    /**
     * Create a new customer
     *
     * @param CustomerCreateInput $input
     * @return CustomerOutput
     */
    public function create(CustomerCreateInput $input): CustomerOutput
    {
        $customer = (new Customer())
            ->setName($input->name)
            ->setEmail($input->email)
            ->setPhone($input->phone);

        $this->customerRepository->save($customer, true);

        return $this->mapper->map($customer, CustomerOutput::class);
    }

    /**
     * Update an existing customer
     *
     * @param CustomerUpdateInput $input
     * @param int $customerId
     * @return CustomerOutput
     */
    public function update(CustomerUpdateInput $input, int $customerId): CustomerOutput
    {
        $customer = $this->customerRepository->find($customerId);
        if (!$customer) {
            throw new CustomerNotFoundException($customerId);
        }

        $input->name && $customer->setName($input->name);
        $input->email && $customer->setEmail($input->email);
        $input->phone && $customer->setPhone($input->phone);

        $this->customerRepository->save($customer, true);

        return $this->mapper->map($customer, CustomerOutput::class);
    }

    /**
     * Delete a customer
     *
     * @param int $customerId
     * @return void
     */
    public function delete(int $customerId): void
    {
        $customer = $this->customerRepository->find($customerId);
        if (!$customer) {
            throw new CustomerNotFoundException($customerId);
        }

        $this->customerRepository->delete($customer, true);
    }
}
