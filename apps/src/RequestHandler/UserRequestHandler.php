<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\User;
use Labstag\Event\UserEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class UserRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity): void
    {
        $this->setArrayCollection($entity);
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new UserEntityEvent($oldEntity, $entity)
        );
    }

    protected function setArrayCollection(User $user): void
    {
        $this->setArrayCollectionUser($user);
    }
}
