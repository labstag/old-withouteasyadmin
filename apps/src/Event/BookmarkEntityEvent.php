<?php

namespace Labstag\Event;

use Labstag\Entity\Bookmark;

class BookmarkEntityEvent
{

    protected Bookmark $newEntity;

    protected Bookmark $oldEntity;

    public function __construct(Bookmark $oldEntity, Bookmark $newEntity)
    {
        $this->oldEntity = $oldEntity;
        $this->newEntity = $newEntity;
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
