<?php

namespace App\Dto\Output\Order;

use App\Entity\OrderProduct;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: OrderProduct::class)]
class OrderProductOutput
{
    public int $id;
    #[Map(source: 'product.id')]
    public int $productId;
    #[Map(source: 'product.name')]
    public string $productName;
    public int $quantity;
    public string $subtotal;
}
