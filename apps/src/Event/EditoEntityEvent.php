<?php

namespace Labstag\Event;

use Labstag\Entity\Edito;

class EditoEntityEvent
{
    public function __construct(protected Edito $oldEntity, protected Edito $newEntity)
    {
    }

    public function getNewEntity(): Edito
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Edito
    {
        return $this->oldEntity;
    }
}
