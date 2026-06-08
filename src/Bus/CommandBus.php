<?php

namespace App\Bus;

use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class CommandBus
{
    public function __construct(
        private MessageBusInterface $bus
    ) {}

    public function dispatch(object $command): CommandResult
    {
        $envelope = $this->bus->dispatch($command);

        /** @var CommandResult $result */
        $result = $envelope->last(HandledStamp::class)->getResult();

        return $result;
    }
}