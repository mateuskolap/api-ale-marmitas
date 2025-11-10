<?php

namespace App\Dto\Input\Payment;

use App\Enum\PaymentMethod;
use Muffe\EnumConstraint\Constraints\Enum;
use Symfony\Component\Validator\Constraints as Assert;

class PaymentCreateInput
{
    public function __construct(
        #[Assert\NotBlank]
        public int                $customerId,
        #[Assert\NotBlank]
        #[Assert\Positive]
        public float              $amount,
        #[Assert\NotBlank]
        public \DateTimeImmutable $date,
        #[Assert\NotBlank]
        #[Enum(PaymentMethod::class)]
        public string             $method,
        public ?string            $notes,
    )
    {
    }
}
