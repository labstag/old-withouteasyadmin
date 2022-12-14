<?php

namespace Labstag\Event;

use Labstag\Entity\Paragraph;

class ParagraphEntityEvent
{
    public function __construct(protected Paragraph $oldEntity, protected Paragraph $newEntity)
    {
    }

    public function getNewEntity(): Paragraph
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Paragraph
    {
        return $this->oldEntity;
    }
}
