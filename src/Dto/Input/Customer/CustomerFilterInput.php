<?php

namespace App\Dto\Input\Customer;

use App\Trait\PaginationTrait;

class CustomerFilterInput
{
    use PaginationTrait;

    public function __construct(
        public ?string $name = null,
        public ?string $emailOrPhone = null,
    )
    {
    }
}
