<?php

namespace Labstag\RequestHandler;

use Labstag\Event\LayoutEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class LayoutRequestHandler extends RequestHandlerLib
{
    public function handle(mixed $oldEntity, mixed $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new LayoutEntityEvent($oldEntity, $entity)
        );
    }
}
