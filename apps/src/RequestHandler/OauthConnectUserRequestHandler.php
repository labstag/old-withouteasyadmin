<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\User;
use Labstag\Event\UserCollectionEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class OauthConnectUserRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        if (!$oldEntity instanceof OauthConnectUser || !$entity instanceof OauthConnectUser) {
            return;
        }

        $user = $entity->getRefuser();
        if (!$user instanceof User) {
            return;
        }

        $this->setArrayCollection($user);
        $userCollectionEvent = new UserCollectionEvent();
        parent::handle($oldEntity, $entity);
        $userCollectionEvent->addOauthConnectUser($oldEntity, $entity);
    }

    protected function setArrayCollection(User $user): void
    {
        $this->setArrayCollectionUser($user);
    }
}
