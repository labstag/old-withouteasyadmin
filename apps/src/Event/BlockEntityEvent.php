<?php

namespace Labstag\Event;

use Labstag\Entity\Block;

class BlockEntityEvent
{
    public function __construct(protected Block $oldEntity, protected Block $newEntity)
    {
    }

    public function getNewEntity(): Block
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Block
    {
        return $this->oldEntity;
    }
}
