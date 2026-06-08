<?php

namespace App\Bus\Middleware;

use App\Bus\CommandResult;
use App\Exception\DomainException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class CommandResultMiddleware implements MiddlewareInterface
{
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            $envelope = $stack->next()->handle($envelope, $stack);

            /** @var HandledStamp|null $stamp */
            $stamp = $envelope->last(HandledStamp::class);

            $result = CommandResult::success(
                data: $stamp?->getResult(),
                code: 200
            );

        } catch (\Throwable $e) {
            // Unwrap HandlerFailedException to get the original exception
            $cause = $e;
            if ($cause instanceof HandlerFailedException) {
                $cause = $cause->getPrevious();
            }

            if ($cause instanceof DomainException) {
                $result = CommandResult::failure(
                    message: $cause->getMessage(),
                    code:    $cause->getCode()
                );
            } else {
                $result = CommandResult::failure(
                    message: $cause?->getMessage() ?? $e->getMessage(),
                    code:    500
                );
            }
        }

        return $envelope->with(
            new HandledStamp($result, static::class)
        );
    }
}