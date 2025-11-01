<?php

namespace App\DTO\Input\Customer;

class CustomerFilterInput
{
    public function __construct(
        public ?string $name,
        public ?string $emailOrPhone
    )
    {
    }
}
