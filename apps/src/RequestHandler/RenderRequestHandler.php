<?php

namespace Labstag\RequestHandler;

use Labstag\Event\RenderEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class RenderRequestHandler extends RequestHandlerLib
{
    public function handle(mixed $oldEntity, mixed $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new RenderEntityEvent($oldEntity, $entity)
        );
    }
}
