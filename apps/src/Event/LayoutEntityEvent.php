<?php

namespace Labstag\Event;

use Labstag\Entity\Layout;

class LayoutEntityEvent
{
    public function __construct(protected Layout $oldEntity, protected Layout $newEntity)
    {
    }

    public function getNewEntity(): Layout
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Layout
    {
        return $this->oldEntity;
    }
}
