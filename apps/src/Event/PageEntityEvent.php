<?php

namespace Labstag\Event;

use Labstag\Entity\Page;

class PageEntityEvent
{
    public function __construct(protected Page $oldEntity, protected Page $newEntity)
    {
    }

    public function getNewEntity(): Page
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Page
    {
        return $this->oldEntity;
    }
}
