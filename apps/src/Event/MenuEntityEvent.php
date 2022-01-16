<?php

namespace Labstag\Event;

use Labstag\Entity\Menu;

class MenuEntityEvent
{
    public function __construct(protected Menu $oldEntity, protected Menu $newEntity)
    {
    }

    public function getNewEntity(): Menu
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Menu
    {
        return $this->oldEntity;
    }
}
