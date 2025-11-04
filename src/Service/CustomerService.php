<?php

namespace App\Service;

use App\DTO\Input\Customer\CustomerCreateInput;
use App\DTO\Input\Customer\CustomerFilterInput;
use App\DTO\Input\Customer\CustomerUpdateInput;
use App\DTO\Input\PaginationOptions;
use App\DTO\Output\Customer\CustomerOutput;
use App\DTO\Output\Pagination\PaginatedList;
use App\Entity\Customer;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;

readonly class CustomerService
{
    public function __construct(
        private PaginatorInterface $paginator,
        private CustomerRepository $customerRepository
    )
    {
    }

    /**
     * Find all customers with pagination
     *
     * @param PaginationOptions $pagination
     * @param CustomerFilterInput|null $filters
     * @return PaginatedList
     */
    public function findAllPaginated(PaginationOptions $pagination, ?CustomerFilterInput $filters = null): PaginatedList
    {
        $pagination = $this->paginator->paginate(
            $this->customerRepository->findFilteredQuery($filters),
            $pagination->page,
            $pagination->size
        );

        $pagination->setItems(array_map(
            fn(Customer $customer) => new CustomerOutput($customer),
            $pagination->getItems()
        ));

        return new PaginatedList($pagination);
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

        return new CustomerOutput($customer);
    }

    /**
     * Update an existing customer
     *
     * @param CustomerUpdateInput $input
     * @param Customer $customer
     * @return CustomerOutput
     */
    public function update(CustomerUpdateInput $input, Customer $customer): CustomerOutput
    {
        $input->name && $customer->setName($input->name);
        $input->email && $customer->setEmail($input->email);
        $input->phone && $customer->setPhone($input->phone);

        $this->customerRepository->save($customer, true);

        return new CustomerOutput($customer);
    }

    /**
     * Delete a customer
     *
     * @param Customer $customer
     * @return void
     */
    public function delete(Customer $customer): void
    {
        $this->customerRepository->delete($customer, true);
    }
}
