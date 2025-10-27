<?php

namespace App\DTO\Input\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserCreateInput
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Email]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\All([new Assert\Type('string')])]
        public array $roles,

        #[Assert\NotBlank]
        #[Assert\PasswordStrength(minScore: 1)]
        public string $password,

        #[Assert\NotBlank]
        #[Assert\EqualTo(propertyPath: "password", message: "Password confirmation does not match.")]
        public string $passwordConfirmation,
    )
    {
    }
}
