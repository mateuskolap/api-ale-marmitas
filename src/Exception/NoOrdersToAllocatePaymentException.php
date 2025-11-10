<?php

namespace App\Exception;

use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;
use Symfony\Component\HttpKernel\Attribute\WithLogLevel;

#[WithLogLevel(LogLevel::WARNING)]
#[WithHttpStatus(Response::HTTP_CONFLICT)]
class NoOrdersToAllocatePaymentException extends \DomainException
{
    public function __construct()
    {
        parent::__construct("No orders available to allocate the payment.");
    }
}
