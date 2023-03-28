<?php

namespace Labstag\Event\Listener;

use Doctrine\ORM\Events;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Lib\EventListenerLib;
use Labstag\Service\HistoryService;

class ChapterListener extends EventListenerLib
{
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

    private function logActivity(string $action, LifecycleEventArgs $lifecycleEventArgs): void
    {
        $object = $lifecycleEventArgs->getObject();
        if (!$object instanceof Chapter) {
            return;
        }

        $this->logger->info($action.' '.$object::class);
        $this->verifMetas($object);
        /** @var History $history */
        $history = $object->getHistory();
        $this->enqueueMethod->enqueue(
            HistoryService::class,
            'process',
            [
                'fileDirectory' => $this->parameterBag->get('file_directory'),
                'historyId'     => $history->getId(),
                'all'           => false,
            ]
        );
    }
}
