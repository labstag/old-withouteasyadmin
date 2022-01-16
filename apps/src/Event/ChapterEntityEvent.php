<?php

namespace Labstag\Event;

use Labstag\Entity\Chapter;

class ChapterEntityEvent
{

    public function __construct(protected Chapter $oldEntity, protected Chapter $newEntity)
    {
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
