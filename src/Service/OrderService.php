<?php

namespace App\Service;

use App\Dto\Input\Order\OrderCreateInput;
use App\Dto\Input\Order\OrderFilterInput;
use App\Dto\Input\PaginationOptions;
use App\Dto\Output\Order\OrderOutput;
use App\Dto\Output\Pagination\PaginatedList;
use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Enum\OrderStatus;
use App\Exception\CustomerNotFoundException;
use App\Exception\OrderNotFoundException;
use App\Exception\ProductNotFoundException;
use App\Repository\CustomerRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;

readonly class OrderService
{
    public function __construct(
        private OrderRepository       $orderRepository,
        private CustomerRepository    $customerRepository,
        private ProductRepository     $productRepository,
        private PaginatorInterface    $paginator,
        private ObjectMapperInterface $mapper,
    )
    {
    }

    /**
     * Show order details
     *
     * @param int $id
     * @return OrderOutput
     */
    public function show(int $id): OrderOutput
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new OrderNotFoundException($id);
        }

        return $this->mapper->map($order, OrderOutput::class);
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
        $paginatedResults = $this->paginator->paginate(
            $this->orderRepository->findFilteredQuery($filters),
            $pagination->page,
            $pagination->size
        );

        $paginatedResults->setItems(array_map(
            fn(Order $order) => $this->mapper->map($order, OrderOutput::class),
            $paginatedResults->getItems()
        ));

        return new PaginatedList($paginatedResults);
    }

    /**
     * Create a new order
     *
     * @param OrderCreateInput $input
     * @return OrderOutput
     */
    public function create(OrderCreateInput $input): OrderOutput
    {
        $customer = $this->customerRepository->find($input->customerId);
        if (!$customer) {
            throw new CustomerNotFoundException($input->customerId);
        }

        $order = new Order();

        $totalOrder = $this->allocateProductsToOrder($order, $input->products);

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

    private function allocateProductsToOrder(Order $order, array $products): string
    {
        $totalOrder = '0.00';

        foreach ($products as $orderProductDto) {
            $product = $this->productRepository->find($orderProductDto->productId);
            if (!$product) {
                throw new ProductNotFoundException($orderProductDto->productId);
            }

            $subtotal = bcmul($product->getPrice(), (string)$orderProductDto->quantity, 2);
            $totalOrder = bcadd($totalOrder, $subtotal, 2);

            $orderProduct = (new OrderProduct())
                ->setProduct($product)
                ->setQuantity($orderProductDto->quantity)
                ->setSubtotal($subtotal);

            $order->addOrderProduct($orderProduct);
        }

        return $totalOrder;
    }
}
