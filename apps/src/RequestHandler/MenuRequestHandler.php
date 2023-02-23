<?php

namespace Labstag\RequestHandler;

use Labstag\Event\MenuEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class MenuRequestHandler extends RequestHandlerLib
{
    public function handle(mixed $oldEntity, mixed $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new MenuEntityEvent($oldEntity, $entity)
        );
    }
}
