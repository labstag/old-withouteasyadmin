<?php

namespace Labstag\RequestHandler;

use Labstag\Event\ChapterEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class ChapterRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new ChapterEntityEvent($oldEntity, $entity)
        );
    }
}
