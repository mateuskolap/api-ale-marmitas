<?php

namespace App\Dto\Input\Order;

use Symfony\Component\Validator\Constraints as Assert;

class OrderCreateInput
{
    public function __construct(
        #[Assert\NotBlank]
        public int $customerId,

        /** @var OrderProductInput[] */
        #[Assert\Count(min: 1)]
        #[Assert\Valid]
        public array $products,
    )
    {
    }
}
