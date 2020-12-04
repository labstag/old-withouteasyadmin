<?php

namespace Labstag\Event;

use Labstag\Entity\User;

class UserEntityEvent
{

    private User $oldEntity;

    private User $newEntity;

    public function __construct(User $oldEntity, User $newEntity)
    {
        $this->oldEntity = $oldEntity;
        $this->newEntity = $newEntity;
    }

    public function getOldEntity(): User
    {
        return $this->oldEntity;
    }

    public function getNewEntity(): User
    {
        return $this->newEntity;
    }
}
