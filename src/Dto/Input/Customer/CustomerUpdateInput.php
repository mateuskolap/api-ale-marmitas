<?php

namespace App\Dto\Input\Customer;

use Symfony\Component\Validator\Constraints as Assert;

class CustomerUpdateInput
{
    public function __construct(
        #[Assert\Length(min: 3, max: 255)]
        public ?string $name,

        #[Assert\Email]
        public ?string $email,

        #[Assert\Length(min: 11, max: 11)]
        public ?string $phone,
    )
    {
    }
}
