<?php

namespace Labstag\Queue\Handler;

use Labstag\Queue\Message\ServiceMethodMessage;
use Labstag\Service\BookmarkService;
use Psr\Container\ContainerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Contracts\Service\ServiceSubscriberInterface;

class ServiceMethodMessageHandler implements MessageHandlerInterface, ServiceSubscriberInterface
{

    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke(ServiceMethodMessage $message): void
    {
        // @var callable $callable
        $callable = [
            $this->container->get($message->getServiceName()),
            $message->getMethod(),
        ];

        call_user_func_array($callable, $message->getParams());
    }

    public static function getSubscribedServices()
    {
        return [
            MailerInterface::class => MailerInterface::class,
            BookmarkService::class => BookmarkService::class,
        ];
    }
}
