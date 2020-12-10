<?php

namespace Labstag\Manager;

use Labstag\Entity\AdresseUser;
use Labstag\Entity\EmailUser;
use Labstag\Entity\LienUser;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;
use Labstag\Event\UserCollectionEvent;
use Psr\EventDispatcher\EventDispatcherInterface;

class UserManager
{

    protected EventDispatcherInterface $dispatcher;

    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    public function setArrayCollection(User $entity): void
    {
        $userCollectionEvent = new UserCollectionEvent();
        $oauthConnectUsers   = $entity->getOauthConnectUsers();
        foreach ($oauthConnectUsers as $row) {
            /** @var OauthConnectUser $row */
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addOauthConnectUser($old, $row);
        }

        $liensUsers = $entity->getLienUsers();
        foreach ($liensUsers as $row) {
            /** @var LienUser $row */
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addLienUser($old, $row);
        }

        $emailUsers = $entity->getEmailUsers();
        foreach ($emailUsers as $row) {
            /** @var EmailUser $row */
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addEmailUser($old, $row);
        }

        $phoneUsers = $entity->getPhoneUsers();
        foreach ($phoneUsers as $row) {
            /** @var PhoneUser $row */
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addPhoneUser($old, $row);
        }

        $adresseUsers = $entity->getAdresseUsers();
        foreach ($adresseUsers as $row) {
            /** @var AdresseUser $row */
            $old = clone $row;
            $row->setRefuser($entity);
            $userCollectionEvent->addAdresseUser($old, $row);
        }

        $this->dispatcher->dispatch($userCollectionEvent);
    }
}
