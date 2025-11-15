<?php

namespace App\Dto\Input\Product;

use App\Enum\ProductCategory;
use App\Trait\PaginationTrait;
use Muffe\EnumConstraint\Constraints\Enum;

class ProductFilterInput
{
    use PaginationTrait;

    public function __construct(
        public ?string $name = null,

        #[Enum(ProductCategory::class)]
        public ?string $category = null,
    )
    {
    }
}
