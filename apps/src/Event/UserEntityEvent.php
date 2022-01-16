<?php

namespace Labstag\Event;

use Labstag\Entity\User;

class UserEntityEvent
{

    public function __construct(protected User $oldEntity, protected User $newEntity)
    {
    }

    public function getNewEntity(): User
    {
        return $this->newEntity;
    }

    public function getOldEntity(): User
    {
        return $this->oldEntity;
    }
}
