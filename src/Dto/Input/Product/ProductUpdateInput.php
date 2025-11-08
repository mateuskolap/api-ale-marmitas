<?php

namespace App\Dto\Input\Product;

use App\Enum\ProductCategory;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class ProductUpdateInput
{
    public function __construct(
        #[Assert\Length(min: 3, max: 128)]
        public ?string $name,

        #[Assert\PositiveOrZero]
        public ?float  $price,

        #[Enum(ProductCategory::class)]
        public ?string $category,
    )
    {
    }
}
