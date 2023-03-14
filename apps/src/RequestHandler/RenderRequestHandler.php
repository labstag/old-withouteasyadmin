<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Render;
use Labstag\Event\RenderEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class RenderRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Render || !$entity instanceof Render) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new RenderEntityEvent($oldEntity, $entity)
        );
    }
}
