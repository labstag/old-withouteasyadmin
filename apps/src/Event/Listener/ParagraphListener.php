<?php

namespace Labstag\Event\Listener;

use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Labstag\Entity\Paragraph;
use Labstag\Interfaces\EntityParagraphInterface;
use Labstag\Service\ParagraphService;
use Psr\Log\LoggerInterface;

class ParagraphListener implements EventSubscriberInterface
{
    public function __construct(
        protected ParagraphService $paragraphService,
        protected EntityManagerInterface $entityManager,
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

    private function eventData(Paragraph $paragraph): void
    {
        $this->paragraphService->setData($paragraph);
    }

    private function init(Paragraph $paragraph): void
    {
        $classentity = $this->paragraphService->getTypeEntity($paragraph);
        if (is_null($classentity)) {
            $this->entityManager->remove($paragraph);
            $this->entityManager->flush();

            return;
        }

        $entity = $this->paragraphService->getEntity($paragraph);
        if (!is_null($entity)) {
            return;
        }

        /** @var EntityParagraphInterface $entity */
        $entity = new $classentity();
        $entity->setParagraph($paragraph);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    private function logActivity(string $action, LifecycleEventArgs $lifecycleEventArgs): void
    {
        $object = $lifecycleEventArgs->getObject();
        if (!$object instanceof Paragraph) {
            return;
        }

        $this->logger->info($action.' '.get_class($object));
        $this->init($object);
        $this->eventData($object);
    }
}
