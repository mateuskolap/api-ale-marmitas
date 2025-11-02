<?php

namespace App\DTO\Output\Order;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Enum\OrderStatus;

class OrderOutput
{
    public int $id;
    public int $customerId;
    public string $totalAmount;
    public OrderStatus $status;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;

    /** @var OrderProductOutput[] */
    public array $products;

    public function __construct(Order $order)
    {
        $this->id = $order->getId();
        $this->customerId = $order->getCustomer()->getId();
        $this->totalAmount = $order->getTotal();
        $this->status = $order->getStatus();
        $this->createdAt = $order->getCreatedAt();
        $this->updatedAt = $order->getUpdatedAt();

        $this->products = $order->getOrderProducts()
            ->map(fn(OrderProduct $product) => new OrderProductOutput($product))
            ->toArray();
    }
}
