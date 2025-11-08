<?php

namespace App\Dto\Input\Order;

use App\Enum\OrderStatus;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class OrderStatusUpdateInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Enum(OrderStatus::class)]
        public string $status,
    )
    {
    }
}
