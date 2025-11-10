<?php

namespace App\Dto\Input\Customer;

class CustomerFilterInput
{
    public function __construct(
        public ?string $name,
        public ?string $emailOrPhone
    )
    {
    }
}
