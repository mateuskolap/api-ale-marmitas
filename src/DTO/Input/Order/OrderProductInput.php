<?php

namespace App\DTO\Input\Order;

use Symfony\Component\Validator\Constraints as Assert;

class OrderProductInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $productId,

        #[Assert\Positive]
        public int $quantity = 1,
    )
    {
    }
}
