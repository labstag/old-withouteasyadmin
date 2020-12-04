<?php

namespace Labstag\Event;

use Labstag\Entity\AdresseUser;
use Labstag\Entity\EmailUser;
use Labstag\Entity\LienUser;
use Labstag\Entity\OauthConnectUser;
use Labstag\Entity\PhoneUser;

class UserCollectionEvent
{

    private array $oauthConnectUser = [];

    private array $lienUser = [];

    private array $emailUser = [];

    private array $phoneUser = [];

    private array $adresseUser = [];

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

    public function addLienUser(LienUser $old, LienUser $new): void
    {
        $this->lienUser = [
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

    public function addPhoneUser(PhoneUser $old, PhoneUser $new): void
    {
        $this->phoneUser = [
            'old' => $old,
            'new' => $new,
        ];
    }

    public function addAdresseUser(AdresseUser $old, AdresseUser $new): void
    {
        $this->adresseUser = [
            'old' => $old,
            'new' => $new,
        ];
    }

    public function getOauthConnectUser(): array
    {
        return $this->oauthConnectUser;
    }

    public function getLienUser(): array
    {
        return $this->lienUser;
    }

    public function getEmailUser(): array
    {
        return $this->emailUser;
    }

    public function getPhoneUser(): array
    {
        return $this->phoneUser;
    }

    public function getAdresseUser(): array
    {
        return $this->adresseUser;
    }
}
