<?php

namespace App\DTO\Output\User;

use App\Entity\User;

class UserOutput
{
    public int $id;
    public string $email;
    public array $roles;
    public \DateTimeInterface $createdAt;
    public \DateTimeInterface $updatedAt;

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->email = $user->getEmail();
        $this->roles = $user->getRoles();
        $this->createdAt = $user->getCreatedAt();
        $this->updatedAt = $user->getUpdatedAt();
    }
}
