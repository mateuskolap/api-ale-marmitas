<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ExceptionListener
{
    #[AsEventListener]
    public function onExceptionEvent(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        $previous = $exception->getPrevious();

        $status = $exception instanceof HttpExceptionInterface
            ? $exception->getStatusCode()
            : $this->getStatusFromAttribute($exception) ?? 500;

        $details = [
            'detail' => $exception->getMessage(),
        ];

        if ($previous instanceof ValidationFailedException) {
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            $details = [
                'detail' => 'Validation Error.',
                'errors' => [],
            ];

            foreach ($previous->getViolations() as $violation) {
                $details['errors'][$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }

        $event->setResponse(new JsonResponse([
            'status' => $status,
            ...$details
        ], $status));
    }

    private function getStatusFromAttribute(object $e): ?int
    {
        $attributes = (new \ReflectionClass($e))->getAttributes(WithHttpStatus::class);
        if (empty($attributes)) {
            return null;
        }
        return $attributes[0]->getArguments()[0] ?? null;
    }
}
