<?php

namespace App\Dto\Input\User;

use App\Enum\Role;
use App\Trait\PaginationTrait;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class UserFilterInput
{
    use PaginationTrait;

    public function __construct(
        public ?string $email = null,

        #[Enum(Role::class)]
        public ?string $role = null,
    )
    {
    }
}
