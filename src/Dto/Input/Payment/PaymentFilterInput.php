<?php

namespace App\Dto\Input\Payment;

use App\Enum\PaymentMethod;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentFilterInput
{
    public function __construct(
        public ?int $customerId,
        public ?\DateTimeInterface $dateFrom,
        #[Assert\GreaterThanOrEqual(propertyPath: 'dateFrom', message: 'The dateTo must be greater than or equal to dateFrom')]
        public ?\DateTimeInterface $dateTo,
        #[Enum(PaymentMethod::class)]
        public ?string $method,
    )
    {
    }
}
