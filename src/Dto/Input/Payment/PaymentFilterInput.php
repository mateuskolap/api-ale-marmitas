<?php

namespace App\Dto\Input\Payment;

use App\Enum\PaymentMethod;
use App\Trait\PaginationTrait;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentFilterInput
{
    use PaginationTrait;

    public function __construct(
        public ?int $customerId = null,
        public ?\DateTimeInterface $dateFrom = null,
        #[Assert\GreaterThanOrEqual(propertyPath: 'dateFrom', message: 'The dateTo must be greater than or equal to dateFrom')]
        public ?\DateTimeInterface $dateTo = null,
        #[Enum(PaymentMethod::class)]
        public ?string $method = null,
    )
    {
    }
}
