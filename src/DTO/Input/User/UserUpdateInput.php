<?php

namespace App\DTO\Input\User;

use Symfony\Component\Validator\Constraints as Assert;

class UserUpdateInput
{
    public function __construct(
        #[Assert\Email]
        public ?string $email,

        #[Assert\All([new Assert\Type('string')])]
        public ?array  $roles,

        #[Assert\PasswordStrength(minScore: 1)]
        public ?string $password,

        #[Assert\When(expression: 'this.password !== null', constraints: [
            new Assert\NotBlank(message: "The password confirmation is required when changing the password."),
            new Assert\EqualTo(propertyPath: "password", message: "Password confirmation does not match."),
        ])]
        public ?string $passwordConfirmation,
    )
    {
    }
}
