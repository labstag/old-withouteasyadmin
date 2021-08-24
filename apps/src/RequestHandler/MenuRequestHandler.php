<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Menu;
use Labstag\Event\MenuEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class MenuRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new MenuEntityEvent($oldEntity, $entity)
        );
    }
}
