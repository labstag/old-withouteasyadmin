<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Bookmark;
use Labstag\Event\BookmarkEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class BookmarkRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Bookmark || !$entity instanceof Bookmark) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new BookmarkEntityEvent($oldEntity, $entity)
        );
    }
}
