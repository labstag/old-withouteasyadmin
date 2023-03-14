<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Page;
use Labstag\Event\PageEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class PageRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Page || !$entity instanceof Page) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new PageEntityEvent($oldEntity, $entity)
        );
    }
}
