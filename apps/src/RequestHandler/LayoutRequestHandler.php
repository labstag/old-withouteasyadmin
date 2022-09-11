<?php

namespace Labstag\RequestHandler;

use Labstag\Event\LayoutEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class LayoutRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new LayoutEntityEvent($oldEntity, $entity)
        );
    }
}
