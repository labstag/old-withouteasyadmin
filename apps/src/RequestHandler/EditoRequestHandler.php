<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Edito;
use Labstag\Event\EditoEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class EditoRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Edito || !$entity instanceof Edito) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new EditoEntityEvent($oldEntity, $entity)
        );
    }
}
