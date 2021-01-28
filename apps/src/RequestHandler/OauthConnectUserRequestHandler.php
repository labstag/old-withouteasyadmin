<?php
namespace Labstag\RequestHandler;

use Labstag\Entity\AdresseUser;
use Labstag\Entity\EmailUser;
use Labstag\Entity\LienUser;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\PhoneUser;
use Labstag\Entity\User;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Event\UserCollectionEvent;

class OauthConnectUserRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        $this->setArrayCollection($entity);
        $userCollectionEvent = new UserCollectionEvent();
        parent::handle($oldEntity, $entity);
        $userCollectionEvent->addOauthConnectUser($oldEntity, $entity);
    }

    protected function setArrayCollection(User $entity)
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
    }
}
