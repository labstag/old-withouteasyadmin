<?php

namespace Labstag\RequestHandler;

use Labstag\Event\PageEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class PageRequestHandler extends RequestHandlerLib
{
    public function handle(mixed $oldEntity, mixed $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new PageEntityEvent($oldEntity, $entity)
        );
    }
}
