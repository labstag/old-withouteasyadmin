<?php

namespace Labstag\RequestHandler;

use Labstag\Event\EditoEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class EditoRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new EditoEntityEvent($oldEntity, $entity)
        );
    }
}
