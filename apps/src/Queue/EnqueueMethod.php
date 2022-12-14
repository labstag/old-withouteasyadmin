<?php

namespace Labstag\Queue;

use DateTimeInterface;
use Labstag\Queue\Message\ServiceMethodMessage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;

class EnqueueMethod
{
    public function __construct(protected MessageBusInterface $messageBus)
    {
    }

    public function enqueue(
        string $service,
        string $method,
        array $params = [],
        ?DateTimeInterface $dateTime = null
    ): void
    {
        $stamps = [];
        // Le service doit être appelé avec un délai
        if (null !== $dateTime) {
            $delay = 1000 * ($dateTime->getTimestamp() - time());
            if ($delay > 0) {
                $stamps[] = new DelayStamp($delay);
            }
        }

        $this->messageBus->dispatch(
            new ServiceMethodMessage($service, $method, $params),
            $stamps
        );
    }
}
