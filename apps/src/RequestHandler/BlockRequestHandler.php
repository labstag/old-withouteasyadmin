<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Block;
use Labstag\Event\BlockEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class BlockRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Block || !$entity instanceof Block) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new BlockEntityEvent($oldEntity, $entity)
        );
    }
}
