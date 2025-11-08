<?php

namespace App\Service;

use App\Dto\Input\PaginationOptions;
use App\Dto\Input\Payment\PaymentFilterInput;
use App\Dto\Output\Pagination\PaginatedList;
use App\Dto\Output\Payment\PaymentOutput;
use App\Entity\Payment;
use App\Repository\PaymentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

readonly class PaymentService
{
    public function __construct(
        private PaginatorInterface    $paginator,
        private PaymentRepository     $paymentRepository,
        private ObjectMapperInterface $mapper
    )
    {
    }

    /**
     * Find all payments with pagination.
     *
     * @param PaginationOptions $pagination
     * @param PaymentFilterInput|null $filters
     * @return PaginatedList
     */
    public function findAllPaginated(PaginationOptions $pagination, ?PaymentFilterInput $filters = null): PaginatedList
    {
        $pagination = $this->paginator->paginate(
            $this->paymentRepository->findFilteredQuery($filters),
            $pagination->page,
            $pagination->size
        );

        $pagination->setItems(array_map(
            fn(Payment $payment) => $this->mapper->map($payment, PaymentOutput::class),
            $pagination->getItems()
        ));

        return new PaginatedList($pagination);
    }
}
