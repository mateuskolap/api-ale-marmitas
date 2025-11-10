<?php

namespace App\Dto\Input\Payment;

use App\Enum\PaymentMethod;
use Muffe\EnumConstraint\Constraints\Enum;

class PaymentUpdateInput
{
    public function __construct(
        public ?\DateTimeImmutable $date,
        #[Enum(PaymentMethod::class)]
        public ?string $method,
        public ?string $notes,
    )
    {
    }
}
