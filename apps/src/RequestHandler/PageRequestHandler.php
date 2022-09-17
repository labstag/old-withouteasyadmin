<?php

namespace Labstag\RequestHandler;

use Labstag\Event\PageEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class PageRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new PageEntityEvent($oldEntity, $entity)
        );
    }
}
