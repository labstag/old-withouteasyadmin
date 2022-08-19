<?php

namespace Labstag\RequestHandler;

use Labstag\Event\BlockEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class BlockRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new BlockEntityEvent($oldEntity, $entity)
        );
    }
}
