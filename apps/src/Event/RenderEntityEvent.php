<?php

namespace Labstag\Event;

use Labstag\Entity\Render;

class RenderEntityEvent
{
    public function __construct(protected Render $oldEntity, protected Render $newEntity)
    {
    }

    public function getNewEntity(): Render
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Render
    {
        return $this->oldEntity;
    }
}
