<?php

namespace App\ObjectMapper;

use App\Dto\Output\Order\OrderProductOutput;
use App\Entity\OrderProduct;
use Symfony\Component\ObjectMapper\ObjectMapperInterface;
use Symfony\Component\ObjectMapper\TransformCallableInterface;

readonly class OrderProductsTransform implements TransformCallableInterface
{
    public function __construct(
        private ObjectMapperInterface $mapper,
    )
    {
    }

    /**
     * @return OrderProductOutput[]
     */
    public function __invoke(mixed $value, object $source, ?object $target): array
    {
        return $value->map(function (OrderProduct $orderProduct) {
            return $this->mapper->map($orderProduct, OrderProductOutput::class);
        })->toArray();
    }
}
