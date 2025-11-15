<?php

namespace App\Trait;

use Symfony\Component\Validator\Constraints as Assert;

trait PaginationTrait
{
    #[Assert\GreaterThanOrEqual(1)]
    public int $page = 1;
    #[Assert\LessThanOrEqual(50)]
    public int $size = 25;
}

