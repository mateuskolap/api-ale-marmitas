<?php

namespace App\Dto\Output\Payment;

use App\Entity\Payment;
use App\Enum\PaymentMethod;
use App\ObjectMapper\OrderPaymentsTransform;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Payment::class)]
class PaymentOutput
{
    public int $id;
    #[Map(source: 'customer.id')]
    public int $customerId;
    public string $amount;
    public \DateTimeInterface $date;
    public PaymentMethod $method;
    public ?string $notes;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;
    /** @var OrderPaymentOutput[] */
    #[Map(source: 'orderPayments', transform: OrderPaymentsTransform::class)]
    public array $orders;
}
