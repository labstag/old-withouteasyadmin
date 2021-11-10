<?php

namespace Labstag\RequestHandler;

use Labstag\Event\BookmarkEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class BookmarkRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new BookmarkEntityEvent($oldEntity, $entity)
        );
    }
}
