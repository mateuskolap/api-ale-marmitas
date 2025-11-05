<?php

namespace App\DTO\Output\User;

use App\Entity\User;
use Symfony\Component\ObjectMapper\Attribute\Map;

#[Map(source: User::class)]
class UserOutput
{
    public int $id;
    public string $email;
    public array $roles;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;
}
