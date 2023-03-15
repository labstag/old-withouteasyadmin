<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Menu;
use Labstag\Event\MenuEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class MenuRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Menu || !$entity instanceof Menu) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new MenuEntityEvent($oldEntity, $entity)
        );
    }
}
