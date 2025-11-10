<?php

namespace App\Dto\Output\Order;

use App\Entity\Order;
use App\Enum\OrderStatus;
use App\ObjectMapper\OrderProductsTransform;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Order::class)]
class OrderOutput
{
    public int $id;
    #[Map(source: 'customer.id')]
    public int $customerId;
    public string $total;
    public OrderStatus $status;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;
    /** @var OrderProductOutput[] */
    #[Map(source: 'orderProducts', transform: OrderProductsTransform::class)]
    public array $products;
}
