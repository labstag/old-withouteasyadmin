<?php

namespace Labstag\EventSubscriber;

use Labstag\Entity\AddressUser;
use Labstag\Entity\LinkUser;
use Labstag\Entity\OauthConnectUser;
use Labstag\Event\UserCollectionEvent;
use Labstag\Lib\EventSubscriberLib;

class UserCollectionSubscriber extends EventSubscriberLib
{
    /**
     * @return array<class-string<UserCollectionEvent>, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [UserCollectionEvent::class => 'onUserCollectionEvent'];
    }

    public function onUserCollectionEvent(UserCollectionEvent $userCollectionEvent): void
    {
        $oauthConnectUser = $userCollectionEvent->getOauthConnectUser();
        $linkUser = $userCollectionEvent->getLinkUser();
        $addressUser = $userCollectionEvent->getAddressUser();

        $this->setOauthConnectUser($oauthConnectUser);
        $this->setLinkUser($linkUser);
        $this->setAddressUser($addressUser);
    }

    protected function setAddressUser(array $data): void
    {
        if (0 == count($data)) {
            return;
        }

        /** @var AddressUser $old */
        $old = $data['old'];
        /** @var AddressUser $new */
        $new = $data['new'];
        if ($old->getId() === $new->getId()) {
            return;
        }

        $this->userMailService->checkNewAddress($new->getRefuser(), $new);
    }

    protected function setLinkUser(array $data): void
    {
        if (0 == count($data)) {
            return;
        }

        /** @var LinkUser $old */
        $old = $data['old'];
        /** @var LinkUser $new */
        $new = $data['new'];
        if ($old->getId() === $new->getId()) {
            return;
        }

        $this->userMailService->checkNewLink($new->getRefuser(), $new);
    }

    protected function setOauthConnectUser(array $data): void
    {
        if (0 == count($data)) {
            return;
        }

        /** @var OauthConnectUser $old */
        $old = $data['old'];
        /** @var OauthConnectUser $new */
        $new = $data['new'];
        if ($old->getId() === $new->getId()) {
            return;
        }

        $this->userMailService->checkNewOauthConnectUser(
            $new->getRefuser(),
            $new
        );
    }
}
