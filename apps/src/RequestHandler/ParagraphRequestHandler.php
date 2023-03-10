<?php

namespace Labstag\RequestHandler;

use Labstag\Event\ParagraphEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class ParagraphRequestHandler extends RequestHandlerLib
{
    public function handle(mixed $oldEntity, mixed $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new ParagraphEntityEvent($oldEntity, $entity)
        );
    }
}
