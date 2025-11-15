<?php

namespace App\Dto\Input\Order;

use App\Enum\OrderStatus;
use App\Trait\PaginationTrait;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class OrderFilterInput
{
    use PaginationTrait;

    public function __construct(
        public ?int                $customerId = null,
        #[Enum(OrderStatus::class)]
        public ?string             $status = null,
        public ?\DateTimeInterface $createdFrom = null,
        #[Assert\GreaterThanOrEqual(propertyPath: 'createdFrom', message: 'The createdTo must be greater than or equal to createdFrom')]
        public ?\DateTimeInterface $createdTo = null,
    )
    {
    }
}
