<?php

namespace Labstag\Event;

use Labstag\Entity\History;

class HistoryEntityEvent
{

    public function __construct(protected History $oldEntity, protected History $newEntity)
    {
    }

    public function getNewEntity(): History
    {
        return $this->newEntity;
    }

    public function getOldEntity(): History
    {
        return $this->oldEntity;
    }
}
