<?php

namespace Labstag\RequestHandler;

use Labstag\Event\BookmarkEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class BookmarkRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new BookmarkEntityEvent($oldEntity, $entity)
        );
    }
}
