<?php

namespace Labstag\RequestHandler;

use Labstag\Event\HistoryEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class HistoryRequestHandler extends RequestHandlerLib
{
    public $dispatcher;
    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new HistoryEntityEvent($oldEntity, $entity)
        );
    }
}
