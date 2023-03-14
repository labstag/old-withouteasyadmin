<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\History;
use Labstag\Event\HistoryEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class HistoryRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof History || !$entity instanceof History) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new HistoryEntityEvent($oldEntity, $entity)
        );
    }
}
