<?php

namespace Labstag\Event;

use Labstag\Entity\AddressUser;
use Labstag\Entity\EmailUser;
use Labstag\Entity\LinkUser;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\PhoneUser;

class UserCollectionEvent
{

    protected array $addressUser = [];

    protected array $emailUser = [];

    protected array $linkUser = [];

    protected array $oauthConnectUser = [];

    protected array $phoneUser = [];

    public function addAddressUser(AddressUser $old, AddressUser $new): void
    {
        $this->addressUser = [
            'old' => $old,
            'new' => $new,
        ];
    }

    public function addEmailUser(EmailUser $old, EmailUser $new): void
    {
        $this->emailUser = [
            'old' => $old,
            'new' => $new,
        ];
    }

    public function addLinkUser(LinkUser $old, LinkUser $new): void
    {
        $this->linkUser = [
            'old' => $old,
            'new' => $new,
        ];
    }

    public function addOauthConnectUser(
        OauthConnectUser $old,
        OauthConnectUser $new
    ): void
    {
        $this->oauthConnectUser = [
            'old' => $old,
            'new' => $new,
        ];
    }

    public function addPhoneUser(PhoneUser $old, PhoneUser $new): void
    {
        $this->phoneUser = [
            'old' => $old,
            'new' => $new,
        ];
    }

    /**
     * @return mixed[]
     */
    public function getAddressUser(): array
    {
        return $this->addressUser;
    }

    /**
     * @return mixed[]
     */
    public function getEmailUser(): array
    {
        return $this->emailUser;
    }

    /**
     * @return mixed[]
     */
    public function getLinkUser(): array
    {
        return $this->linkUser;
    }

    /**
     * @return mixed[]
     */
    public function getOauthConnectUser(): array
    {
        return $this->oauthConnectUser;
    }

    /**
     * @return mixed[]
     */
    public function getPhoneUser(): array
    {
        return $this->phoneUser;
    }
}
