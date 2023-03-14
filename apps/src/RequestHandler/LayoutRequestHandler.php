<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Layout;
use Labstag\Event\LayoutEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class LayoutRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Layout || !$entity instanceof Layout) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new LayoutEntityEvent($oldEntity, $entity)
        );
    }
}
