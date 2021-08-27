<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\User;
use Labstag\Event\UserEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class UserRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        $this->setArrayCollection($entity);
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new UserEntityEvent($oldEntity, $entity)
        );
    }

    protected function setArrayCollection(User $entity)
    {
        $this->setArrayCollectionUser($entity);
    }
}
