<?php

namespace App\DTO\Output\Product;

use App\Entity\Product;

class ProductOutput
{
    public int $id;
    public string $name;
    public float $price;
    public string $category;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;

    public function __construct(Product $product)
    {
        $this->id = $product->getId();
        $this->name = $product->getName();
        $this->price = $product->getPrice();
        $this->category = $product->getCategory()->value;
        $this->createdAt = $product->getCreatedAt();
        $this->updatedAt = $product->getUpdatedAt();
    }
}
