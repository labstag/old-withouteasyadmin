<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\User;
use Labstag\Event\UserCollectionEvent;
use Labstag\Lib\RequestHandlerLib;

class OauthConnectUserRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        $this->setArrayCollection($entity->getRefuser());
        $userCollectionEvent = new UserCollectionEvent();
        parent::handle($oldEntity, $entity);
        $userCollectionEvent->addOauthConnectUser($oldEntity, $entity);
    }

    protected function setArrayCollection(User $user)
    {
        $this->setArrayCollectionUser($user);
    }
}
