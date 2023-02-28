<?php

namespace Labstag\Queue\Handler;

use Labstag\Queue\Message\ServiceMethodMessage;
use Labstag\Service\BookmarkService;
use Labstag\Service\HistoryService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

#[AsMessageHandler]
class ServiceMethodMessageHandler implements ServiceSubscriberInterface
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

    /**
     * @return array<string, string>
     */
    public static function getSubscribedServices(): array
    {
        return [
            MailerInterface::class => MailerInterface::class,
            BookmarkService::class => BookmarkService::class,
            HistoryService::class  => HistoryService::class,
        ];
    }
}
