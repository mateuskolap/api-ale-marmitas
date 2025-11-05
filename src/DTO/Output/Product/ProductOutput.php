<?php

namespace App\DTO\Output\Product;

use App\Entity\Product;
use App\Enum\ProductCategory;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Product::class)]
class ProductOutput
{
    public int $id;
    public string $name;
    public float $price;
    public ProductCategory $category;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;
}
