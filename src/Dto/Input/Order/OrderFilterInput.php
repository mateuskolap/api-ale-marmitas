<?php

namespace App\Dto\Input\Order;

use App\Enum\OrderStatus;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class OrderFilterInput
{
    public function __construct(
        public ?int                $customerId,
        #[Enum(OrderStatus::class)]
        public ?string             $status,
        public ?\DateTimeInterface $createdFrom,
        #[Assert\GreaterThanOrEqual(propertyPath: 'createdFrom', message: 'The createdTo must be greater than or equal to createdFrom')]
        public ?\DateTimeInterface $createdTo,
    )
    {
    }
}
