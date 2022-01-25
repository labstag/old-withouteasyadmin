<?php

namespace Labstag\Event;

use Labstag\Entity\Attachment;

class AttachmentEntityEvent
{
    public function __construct(protected Attachment $oldEntity, protected Attachment $newEntity)
    {
    }

    public function getNewEntity(): Attachment
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Attachment
    {
        return $this->oldEntity;
    }
}
