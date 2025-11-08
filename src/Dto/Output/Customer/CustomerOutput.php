<?php

namespace App\Dto\Output\Customer;

use App\Entity\Customer;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: Customer::class)]
class CustomerOutput
{
    public int $id;
    public string $name;
    public ?string $email;
    public ?string $phone;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;
}
