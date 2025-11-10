<?php

namespace App\Dto\Output\Payment;

use App\Entity\OrderPayment;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: OrderPayment::class)]
class OrderPaymentOutput
{
    public int $id;
    #[Map(source: 'order.id')]
    public int $orderId;
    public string $amountApplied;
}
