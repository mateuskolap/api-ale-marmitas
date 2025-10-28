<?php

namespace App\DTO\Input\User;

use App\Enum\Role;
use Muffe\EnumConstraint\Constraints\Enum;

class UserFilterInput
{
    public function __construct(
        public ?string $email,

        #[Enum(Role::class)]
        public ?string $role
    )
    {
    }
}
