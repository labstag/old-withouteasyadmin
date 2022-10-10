<?php

namespace Labstag\Event;

use Labstag\Entity\Post;

class PostEntityEvent
{
    public function __construct(protected Post $oldEntity, protected Post $newEntity)
    {
    }

    public function getNewEntity(): Post
    {
        return $this->newEntity;
    }

    public function getOldEntity(): Post
    {
        return $this->oldEntity;
    }
}
