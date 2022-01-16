<?php

namespace Labstag\Event;

use Labstag\Entity\Bookmark;

class BookmarkEntityEvent
{

    public function __construct(protected Bookmark $oldEntity, protected Bookmark $newEntity)
    {
    }

    public function getNewEntity(): Bookmark
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Bookmark
    {
        return $this->oldEntity;
    }
}
