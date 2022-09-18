<?php

namespace Labstag\RequestHandler;

use Labstag\Event\BlockEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class BlockRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new BlockEntityEvent($oldEntity, $entity)
        );
    }
}
