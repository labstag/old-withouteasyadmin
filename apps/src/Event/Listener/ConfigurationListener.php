<?php

namespace Labstag\Event\Listener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Exception;
use Labstag\Entity\Configuration;
use Labstag\Repository\ConfigurationRepository;
use Labstag\Service\ErrorService;
use Labstag\Service\SessionService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ConfigurationListener implements EventSubscriberInterface
{
    public function __construct(
        protected CacheInterface $cache,
        protected ConfigurationRepository $configurationRepository,
        protected ParameterBagInterface $parameterBag,
        protected SessionService $sessionService,
        protected TranslatorInterface $translator,
        protected ErrorService $errorService,
        protected LoggerInterface $logger
    )
    {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postRemove,
            Events::postUpdate,
        ];
    }

    public function postPersist(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('persist', $lifecycleEventArgs);
    }

    public function postRemove(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('remove', $lifecycleEventArgs);
    }

    public function postUpdate(LifecycleEventArgs $lifecycleEventArgs): void
    {
        $this->logActivity('update', $lifecycleEventArgs);
    }

    private function execute(Configuration $configuration): void
    {
        $this->cache->delete('configuration');
        $this->setRobotsTxt($configuration);
    }

    private function logActivity(string $action, LifecycleEventArgs $lifecycleEventArgs): void
    {
        unset($action);
        $object = $lifecycleEventArgs->getObject();
        if (!$object instanceof Configuration) {
            return;
        }

        $this->execute($object);
    }

    private function setRobotsTxt(Configuration $configuration): void
    {
        if ($configuration->getName() != 'robotstxt') {
            return;
        }

        try {
            $value = $configuration->getValue();
            $file  = 'robots.txt';
            if (is_file($file)) {
                unlink($file);
            }

            file_put_contents($file, $value);
            $msg = $this->translator->trans('admin.robotstxt.file', ['%file%' => $file]);
            $this->logger->info($msg);
            $this->sessionService->flashBagAdd('success', $msg);
        } catch (Exception $exception) {
            $this->errorService->set($exception);
        }
    }
}
