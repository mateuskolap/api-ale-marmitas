<?php

namespace App\Dto\Input\Order;

class OrderFilterInput
{
    public function __construct(
        public ?int $customerId,
        public ?string $status,
        public ?\DateTimeInterface $createdFrom,
        public ?\DateTimeInterface $createdTo,
    )
    {
    }
}
