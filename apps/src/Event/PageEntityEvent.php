<?php

namespace Labstag\Event;

use Labstag\Entity\Page;

class PageEntityEvent
{

    protected Page $newEntity;

    protected Page $oldEntity;

    public function __construct(Page $oldEntity, Page $newEntity)
    {
        $this->oldEntity = $oldEntity;
        $this->newEntity = $newEntity;
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
