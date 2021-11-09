<?php

namespace Labstag\Event;

use Labstag\Entity\Chapter;

class ChapterEntityEvent
{

    protected Chapter $newEntity;

    protected Chapter $oldEntity;

    public function __construct(Chapter $oldEntity, Chapter $newEntity)
    {
        $this->oldEntity = $oldEntity;
        $this->newEntity = $newEntity;
    }

    public function getNewEntity(): Chapter
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Chapter
    {
        return $this->oldEntity;
    }
}
