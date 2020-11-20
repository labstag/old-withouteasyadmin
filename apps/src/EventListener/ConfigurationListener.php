<?php
namespace Labstag\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Exception;
use Labstag\Entity\Configuration;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

class ConfigurationListener implements EventSubscriber
{

    protected RouterInterface $router;

    protected SessionInterface $session;

    protected LoggerInterface $logger;

    public function __construct(
        RouterInterface $router,
        SessionInterface $session,
        LoggerInterface $logger
    )
    {
        $this->logger  = $logger;
        $this->session = $session;
        $this->router  = $router;
    }

    /**
     * Sur quoi écouter.
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
            Events::preUpdate,
            Events::prePersist,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $manager       = $args->getEntityManager();
        $uow           = $manager->getUnitOfWork();
        $entityUpdates = $uow->getScheduledEntityUpdates();
        if (count($entityUpdates) === 0) {
            return;
        }

        foreach ($entityUpdates as $entity) {
            if (! $entity instanceof Configuration) {
                continue;
            }

            $this->setRobotsTxt($entity);
            $meta = $manager->getClassMetadata(get_class($entity));
            $manager->getUnitOfWork()->recomputeSingleEntityChangeSet(
                $meta,
                $entity
            );
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (! $entity instanceof Configuration) {
            return;
        }

        $manager = $args->getEntityManager();
        $this->setRobotsTxt($entity);
        $meta = $manager->getClassMetadata(get_class($entity));
        $manager->getUnitOfWork()->recomputeSingleEntityChangeSet(
            $meta,
            $entity
        );
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        if (! $entity instanceof Configuration) {
            return;
        }

        $manager = $args->getEntityManager();
        $this->setRobotsTxt($entity);
    }

    private function setRobotsTxt(Configuration $entity): void
    {
        $name  = $entity->getName();
        $value = $entity->getValue();
        try {
            if ('robotstxt' == $name) {
                file_put_contents('robots.txt', $value);
            }
        } catch (Exception $exception) {
            $this->logger->error($exception->getMessage());
            /** @var Session $session */
            $session = $this->session;
            $session->getFlashBag()->add(
                'danger',
                (string) $exception->getMessage()
            );
        }
    }
}
