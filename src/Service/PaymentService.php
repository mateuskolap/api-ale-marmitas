<?php

namespace App\Service;

use App\Dto\Input\PaginationOptions;
use App\Dto\Input\Payment\PaymentCreateInput;
use App\Dto\Input\Payment\PaymentFilterInput;
use App\Dto\Input\Payment\PaymentUpdateInput;
use App\Dto\Output\Pagination\PaginatedList;
use App\Dto\Output\Payment\PaymentOutput;
use App\Entity\OrderPayment;
use App\Entity\Payment;
use App\Enum\PaymentMethod;
use App\Exception\CustomerNotFoundException;
use App\Exception\NoOrdersToAllocatePaymentException;
use App\Exception\PaymentNotFoundException;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\PaymentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

readonly class PaymentService
{
    public function __construct(
        private PaginatorInterface    $paginator,
        private PaymentRepository     $paymentRepository,
        private CustomerRepository    $customerRepository,
        private OrderRepository       $orderRepository,
        private ObjectMapperInterface $mapper,

    )
    {
    }

    /**
     * Show a single payment.
     *
     * @param int $id
     * @return PaymentOutput
     */
    public function show(int $id): PaymentOutput
    {
        $payment = $this->paymentRepository->find($id);
        if (!$payment) {
            throw new PaymentNotFoundException($id);
        }

        return $this->mapper->map($payment, PaymentOutput::class);
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
        $paginatedResults = $this->paginator->paginate(
            $this->paymentRepository->findFilteredQuery($filters),
            $pagination->page,
            $pagination->size
        );

        $paginatedResults->setItems(array_map(
            fn(Payment $payment) => $this->mapper->map($payment, PaymentOutput::class),
            $paginatedResults->getItems()
        ));

        return new PaginatedList($paginatedResults);
    }

    /**
     * Create a new payment.
     *
     * @param PaymentCreateInput $input
     * @return PaymentOutput
     * @throws CustomerNotFoundException
     * @throws NoOrdersToAllocatePaymentException
     */
    public function create(PaymentCreateInput $input): PaymentOutput
    {
        $customer = $this->customerRepository->find($input->customerId);
        if (!$customer) {
            throw new CustomerNotFoundException($input->customerId);
        }

        $payment = (new Payment())
            ->setCustomer($customer)
            ->setAmount((string)$input->amount)
            ->setDate($input->date)
            ->setMethod(PaymentMethod::from($input->method))
            ->setNotes($input->notes);

        $this->allocatePaymentToOrders($payment);

        if ($payment->getOrderPayments()->isEmpty()) {
            throw new NoOrdersToAllocatePaymentException();
        }

        $changeGiven = bcsub($payment->getAmount(), $payment->getAmountApplied(), 2);
        $payment->setChangeGiven(bccomp($changeGiven, '0.00', 2) > 0 ? $changeGiven : '0.00');

        $this->paymentRepository->save($payment, true);

        return $this->mapper->map($payment, PaymentOutput::class);
    }

    /**
     * Update an existing payment.
     *
     * @param PaymentUpdateInput $input
     * @param Payment $payment
     * @return PaymentOutput
     */
    public function update(PaymentUpdateInput $input, Payment $payment): PaymentOutput
    {
        $input->date && $payment->setDate($input->date);
        $input->method && $payment->setMethod(PaymentMethod::from($input->method));
        $input->notes && $payment->setNotes($input->notes);

        $this->paymentRepository->save($payment, true);

        return $this->mapper->map($payment, PaymentOutput::class);
    }

    /**
     * Delete a payment.
     *
     * @param Payment $payment
     * @return void
     */
    public function delete(Payment $payment): void
    {
        $this->paymentRepository->remove($payment, true);
    }

    /**
     * Allocate payment amount to customer's incomplete orders.
     *
     * @param Payment $payment
     * @return void
     */
    private function allocatePaymentToOrders(Payment $payment): void
    {
        $customer = $payment->getCustomer();
        $toAllocate = $payment->getAmount();
        $incompleteOrders = $this->orderRepository->findWithIncompletePaymentByCustomer($customer);

        foreach ($incompleteOrders as $order) {
            if (bccomp($toAllocate, '0.00', 2) <= 0) {
                break;
            }

            $remainingAmount = bcsub($order->getTotal(), $order->getTotalPaid(), 2);
            if (bccomp($remainingAmount, '0.00', 2) <= 0) {
                continue;
            }

            $amountApplied = bccomp($remainingAmount, $toAllocate, 2) <= 0 ? $remainingAmount : $toAllocate;

            $payment->addOrderPayment(
                (new OrderPayment())
                    ->setOrder($order)
                    ->setPayment($payment)
                    ->setAmountApplied($amountApplied)
            );

            $toAllocate = bcsub($toAllocate, $amountApplied, 2);
        }
    }
}
