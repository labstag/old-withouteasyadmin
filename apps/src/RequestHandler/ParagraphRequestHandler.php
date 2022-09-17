<?php

namespace Labstag\RequestHandler;

use Labstag\Event\ParagraphEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class ParagraphRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity): void
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new ParagraphEntityEvent($oldEntity, $entity)
        );
    }
}
