<?php

namespace App\DTO\Input\Product;

use App\Enum\ProductCategory;
use Muffe\EnumConstraint\Constraints\Enum;

class ProductFilterInput
{
    public function __construct(
        public ?string $name,

        #[Enum(ProductCategory::class)]
        public ?string $category
    )
    {
    }
}
