<?php

namespace App\DTO\Input;

use Symfony\Component\Validator\Constraints as Assert;

class PaginationOptions
{
    public function __construct(
        #[Assert\GreaterThanOrEqual(1)]
        public int $page = 1,

        #[Assert\LessThanOrEqual(50)]
        public int $size = 25,
    )
    {
    }
}
