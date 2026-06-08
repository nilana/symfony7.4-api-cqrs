<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;

#[AsEventListener(event: KernelEvents::EXCEPTION, priority: 100)]
class ExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        $request   = $event->getRequest();
        $exception = $event->getThrowable();

        // Handle #[MapRequestPayload] validation errors
        if (
            $exception instanceof HttpException &&
            $exception->getPrevious() instanceof ValidationFailedException
        ) {
            $violations = $exception->getPrevious()->getViolations();

            $errors = [];
            foreach ($violations as $violation) {
                $errors[] = [
                    'field'   => $violation->getPropertyPath(),
                    'message' => $violation->getMessage()
                ];
            }

            $event->setResponse(new JsonResponse([
                'status'  => 422,
                'message' => 'Validation failed',
                'errors'  => $errors
            ], 422));

            return;
        }

        // Handle all other HTTP exceptions (401, 403, 404 etc.)
        if ($exception instanceof HttpException) {
            $event->setResponse(new JsonResponse([
                'status'  => $exception->getStatusCode(),
                'message' => $exception->getMessage()
            ], $exception->getStatusCode()));

            return;
        }

        // Handle all other unexpected exceptions
        $event->setResponse(new JsonResponse([
            'status'  => 500,
            'message' => 'unexpected error'
        ], 500));
    }
}