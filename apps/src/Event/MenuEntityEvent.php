<?php

namespace Labstag\Event;

use Labstag\Entity\Menu;

class MenuEntityEvent
{

    protected Menu $newEntity;

    protected Menu $oldEntity;

    public function __construct(Menu $oldEntity, Menu $newEntity)
    {
        $this->oldEntity = $oldEntity;
        $this->newEntity = $newEntity;
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
