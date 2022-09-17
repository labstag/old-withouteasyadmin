<?php

namespace Labstag\RequestHandler;

use Labstag\Event\ChapterEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class ChapterRequestHandler extends RequestHandlerLib
{

    public $dispatcher;

    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->eventDispatcher->dispatch(
            new ChapterEntityEvent($oldEntity, $entity)
        );
    }
}
