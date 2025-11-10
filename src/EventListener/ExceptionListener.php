<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $e = $event->getThrowable();

        $statusCode = $e instanceof HttpExceptionInterface
            ? $e->getStatusCode()
            : $this->getStatusFromAttribute($e) ?? 500;

        $response = new JsonResponse([
            'status' => $statusCode,
            'error' => [
                'title' => $e::class,
                'message' => $e->getMessage(),
                'timestamp' => (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM),
            ],
        ], $statusCode);

        $event->setResponse($response);
    }

    private function getStatusFromAttribute(object $e): ?int
    {
        $attr = (new \ReflectionClass($e))->getAttributes(WithHttpStatus::class)[0] ?? null;
        return $attr?->getArguments()[0] ?? null;
    }
}
