<?php

namespace Labstag\RequestHandler;

use Labstag\Event\HistoryEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class HistoryRequestHandler extends RequestHandlerLib
{
    public function handle(mixed $oldEntity, mixed $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new HistoryEntityEvent($oldEntity, $entity)
        );
    }
}
