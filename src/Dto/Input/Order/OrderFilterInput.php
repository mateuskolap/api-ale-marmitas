<?php

namespace App\Dto\Input\Order;

use App\Enum\OrderStatus;
use Muffe\EnumConstraint\Constraints\Enum;

class OrderFilterInput
{
    public function __construct(
        public ?int $customerId,
        #[Enum(OrderStatus::class)]
        public ?string $status,
        public ?\DateTimeInterface $createdFrom,
        public ?\DateTimeInterface $createdTo,
    )
    {
    }
}
