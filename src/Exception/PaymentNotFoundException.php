<?php

namespace App\Exception;

use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;
use Symfony\Component\HttpKernel\Attribute\WithLogLevel;

#[WithLogLevel(LogLevel::INFO)]
#[WithHttpStatus(Response::HTTP_NOT_FOUND)]
class PaymentNotFoundException extends \DomainException
{
    public function __construct(int $id)
    {
        parent::__construct("Payment with id '{$id}' not found.");
    }
}
