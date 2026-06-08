<?php

namespace App\Bus;

use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class QueryBus
{
    public function __construct(
        private MessageBusInterface $bus
    ) {}

    public function dispatch(object $query): mixed
    {
        $envelope = $this->bus->dispatch($query);

        return $envelope->last(HandledStamp::class)->getResult();
    }
}