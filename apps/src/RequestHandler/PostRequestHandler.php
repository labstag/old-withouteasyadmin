<?php

namespace Labstag\RequestHandler;

use Labstag\Event\PostEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class PostRequestHandler extends RequestHandlerLib
{
    public function handle(mixed $oldEntity, mixed $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new PostEntityEvent($oldEntity, $entity)
        );
    }
}
