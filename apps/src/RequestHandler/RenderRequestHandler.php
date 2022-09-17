<?php

namespace Labstag\RequestHandler;

use Labstag\Event\RenderEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class RenderRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new RenderEntityEvent($oldEntity, $entity)
        );
    }
}
