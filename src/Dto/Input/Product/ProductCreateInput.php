<?php

namespace App\Dto\Input\Product;

use App\Enum\ProductCategory;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class ProductCreateInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Length(min: 3, max: 128)]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\PositiveOrZero]
        public float  $price,

        #[Assert\NotBlank]
        #[Enum(ProductCategory::class)]
        public string $category,
    )
    {
    }
}
