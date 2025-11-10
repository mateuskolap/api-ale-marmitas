<?php

namespace App\ObjectMapper;

use App\Dto\Output\Payment\OrderPaymentOutput;
use App\Entity\OrderPayment;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

readonly class OrderPaymentsTransform implements TransformCallableInterface
{
    public function __construct(
        private ObjectMapperInterface $mapper
    )
    {
    }

    /**
     * @return OrderPaymentOutput[]
     */
    public function __invoke(mixed $value, object $source, ?object $target): array
    {
        return $value->map(function (OrderPayment $orderPayment) {
            return $this->mapper->map($orderPayment, OrderPaymentOutput::class);
        })->toArray();
    }
}
