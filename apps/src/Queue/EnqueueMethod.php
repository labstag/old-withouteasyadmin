<?php

namespace Labstag\Queue;

use DateTimeInterface;
use Labstag\Queue\Message\ServiceMethodMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class EnqueueMethod
{

    protected MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        $this->bus = $bus;
    }

    public function enqueue(
        string $service,
        string $method,
        array $params = [],
        DateTimeInterface $date = null
    ): void
    {
        $stamps = [];
        // Le service doit être appelé avec un délai
        if (null !== $date) {
            $delay = 1000 * ($date->getTimestamp() - time());
            if ($delay > 0) {
                $stamps[] = new DelayStamp($delay);
            }
        }

        $this->bus->dispatch(
            new ServiceMethodMessage($service, $method, $params),
            $stamps
        );
    }
}
