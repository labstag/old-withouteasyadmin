<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\User;
use Labstag\Event\UserEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class UserRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        $this->setArrayCollection($entity);
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof User || !$entity instanceof User) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new UserEntityEvent($oldEntity, $entity)
        );
    }

    protected function setArrayCollection(EntityInterface $entity): void
    {
        if (!$entity instanceof User) {
            return;
        }

        $this->setArrayCollectionUser($entity);
    }
}
