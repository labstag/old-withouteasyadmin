<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Post;
use Labstag\Event\PostEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class PostRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Post || !$entity instanceof Post) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new PostEntityEvent($oldEntity, $entity)
        );
    }
}
