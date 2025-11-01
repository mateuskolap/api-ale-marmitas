<?php

namespace App\DTO\Output\Customer;

use App\Entity\Customer;

class CustomerOutput
{
    public int $id;
    public string $name;
    public ?string $email;
    public ?string $phone;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;

    public function __construct(Customer $customer)
    {
        $this->id = $customer->getId();
        $this->name = $customer->getName();
        $this->email = $customer->getEmail();
        $this->phone = $customer->getPhone();
        $this->createdAt = $customer->getCreatedAt();
        $this->updatedAt = $customer->getUpdatedAt();
    }

}
