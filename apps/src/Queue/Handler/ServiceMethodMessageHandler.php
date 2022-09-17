<?php

namespace Labstag\Queue\Handler;

use Labstag\Queue\Message\ServiceMethodMessage;
use Labstag\Service\BookmarkService;
use Labstag\Service\HistoryService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ServiceMethodMessageHandler implements MessageHandlerInterface, ServiceSubscriberInterface
{
    public function __construct(protected ContainerInterface $container)
    {
    }

    public function __invoke(ServiceMethodMessage $serviceMethodMessage): void
    {
        // @var callable $callable
        $callable = [
            $this->container->get($serviceMethodMessage->getServiceName()),
            $serviceMethodMessage->getMethod(),
        ];

        call_user_func_array($callable, $serviceMethodMessage->getParams());
    }

    public static function getSubscribedServices(): array
    {
        return [
            MailerInterface::class => MailerInterface::class,
            BookmarkService::class => BookmarkService::class,
            HistoryService::class  => HistoryService::class,
        ];
    }
}
