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
        $e = $event->getThrowable();

        $status = $e instanceof HttpExceptionInterface
            ? $e->getStatusCode()
            : $this->getStatusFromAttribute($e) ?? 500;

        $error = [
            'title' => $e::class,
            'timestamp' => (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM),
        ];

        $validationException = $this->extractValidationException($e);

        if ($validationException) {
            $status = Response::HTTP_UNPROCESSABLE_ENTITY;
            $error['errors'] = $this->formatViolations($validationException);
        } else {
            $this->setMessageOrLines($error, $e->getMessage());
        }

        $event->setResponse(new JsonResponse([
            'status' => $status,
            'error' => $error
        ], $status));
    }

    private function extractValidationException(\Throwable $e): ?ValidationFailedException
    {
        if ($e instanceof ValidationFailedException) {
            return $e;
        }

        $previous = $e->getPrevious();
        if ($previous instanceof ValidationFailedException) {
            return $previous;
        }

        return null;
    }

    private function formatViolations(ValidationFailedException $e): array
    {
        $errors = [];
        foreach ($e->getViolations() as $v) {
            $errors[] = [
                'property' => $v->getPropertyPath(),
                'message' => $v->getMessage(),
            ];
        }
        return $errors;
    }

    private function setMessageOrLines(array &$error, string $message): void
    {
        if (str_contains($message, "\n")) {
            $error['errors'] = array_values(array_filter(
                array_map('trim', explode("\n", $message)),
                fn($msg) => $msg !== ''
            ));
        } else {
            $error['message'] = $message;
        }
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
