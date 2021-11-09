<?php

namespace Labstag\Event;

use Labstag\Entity\History;

class HistoryEntityEvent
{

    protected History $newEntity;

    protected History $oldEntity;

    public function __construct(History $oldEntity, History $newEntity)
    {
        $this->oldEntity = $oldEntity;
        $this->newEntity = $newEntity;
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
