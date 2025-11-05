<?php

namespace App\Service;

use App\DTO\Input\Order\OrderCreateInput;
use App\DTO\Input\Order\OrderFilterInput;
use App\DTO\Input\PaginationOptions;
use App\DTO\Output\Order\OrderOutput;
use App\DTO\Output\Pagination\PaginatedList;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Enum\OrderStatus;
use App\Exception\CustomerNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

readonly class OrderService
{
    public function __construct(
        private OrderRepository    $orderRepository,
        private CustomerRepository $customerRepository,
        private ProductRepository  $productRepository,
        private PaginatorInterface $paginator,
        private ObjectMapperInterface $mapper,
    )
    {
    }

    /**
     * Find all orders with pagination
     *
     * @param PaginationOptions $pagination
     * @param OrderFilterInput|null $filters
     * @return PaginatedList
     */
    public function findAllPaginated(PaginationOptions $pagination, ?OrderFilterInput $filters = null): PaginatedList
    {
        $pagination = $this->paginator->paginate(
            $this->orderRepository->findFilteredQuery($filters),
            $pagination->page,
            $pagination->size
        );

        $pagination->setItems(array_map(
            fn(Order $order) => $this->mapper->map($order, OrderOutput::class),
            $pagination->getItems()
        ));

        return new PaginatedList($pagination);
    }

    /**
     * Create a new order
     *
     * @param OrderCreateInput $input
     * @return OrderOutput
     * @throws UnprocessableEntityHttpException
     */
    public function create(OrderCreateInput $input): OrderOutput
    {
        $customer = $this->customerRepository->find($input->customerId);
        if (!$customer) {
            throw new CustomerNotFoundException($input->customerId);
        }

        $order = new Order();

        $totalOrder = '0.00';

        foreach ($input->products as $orderProductDTO) {
            $product = $this->productRepository->find($orderProductDTO->productId);
            if (!$product) {
                throw new ProductNotFoundException($orderProductDTO->productId);
            }

            $subTotal = bcmul($product->getPrice(), (string)$orderProductDTO->quantity, 2);
            $totalOrder = bcadd($totalOrder, $subTotal, 2);

            $orderProduct = (new OrderProduct())
                ->setProduct($product)
                ->setQuantity($orderProductDTO->quantity)
                ->setSubtotal($subTotal);

            $order->addOrderProduct($orderProduct);
        }

        $order
            ->setCustomer($customer)
            ->setStatus(OrderStatus::PENDING)
            ->setTotal($totalOrder);

        $this->orderRepository->save($order, true);

        return $this->mapper->map($order, OrderOutput::class);
    }

    /**
     * Update the status of an order
     *
     * @param Order $order
     * @param OrderStatus $status
     * @return OrderOutput
     */
    public function updateStatus(Order $order, OrderStatus $status): OrderOutput
    {
        $order->setStatus($status);

        $this->orderRepository->save($order);

        return $this->mapper->map($order, OrderOutput::class);
    }
}
