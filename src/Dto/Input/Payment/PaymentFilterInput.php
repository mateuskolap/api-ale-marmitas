<?php

namespace App\Dto\Input\Payment;

use App\Enum\PaymentMethod;
use Muffe\EnumConstraint\Constraints\Enum;

class PaymentFilterInput
{
    public function __construct(
        public ?int $customerId,
        public ?\DateTimeInterface $dateFrom,
        public ?\DateTimeInterface $dateTo,
        #[Enum(PaymentMethod::class)]
        public ?string $method,
    )
    {
    }
}
