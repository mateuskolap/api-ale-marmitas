<?php

namespace App\DTO\Output\Order;

use App\Entity\OrderProduct;

class OrderProductOutput
{
    public int $id;
    public int $productId;
    public string $productName;
    public int $quantity;
    public string $subTotal;

    public function __construct(OrderProduct $item)
    {
        $this->id = $item->getId();
        $this->productId = $item->getProduct()->getId();
        $this->productName = $item->getProduct()->getName();
        $this->quantity = $item->getQuantity();
        $this->subTotal = $item->getSubtotal();
    }
}
