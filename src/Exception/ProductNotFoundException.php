<?php

namespace App\Exception;

use Psr\Log\LogLevel;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;
use Symfony\Component\HttpKernel\Attribute\WithLogLevel;

#[WithLogLevel(LogLevel::INFO)]
#[WithHttpStatus(Response::HTTP_NOT_FOUND)]
class ProductNotFoundException extends \DomainException
{
    public function __construct(int $productId)
    {
        parent::__construct("Product with id {$productId} not found.");
    }
}
