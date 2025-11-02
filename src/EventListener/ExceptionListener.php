<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

final class ExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $statusCode = 500;
        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();
        }

        $responseData = [
            'success' => false,
            'status' => $statusCode,
            'error' => [
                'title' => get_class($exception),
                'message' => $exception->getMessage(),
                'timestamp' => (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM),
            ]
        ];

        $response = new JsonResponse($responseData, $statusCode);
        $event->setResponse($response);
    }
}
