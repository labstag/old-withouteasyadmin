<?php

namespace Labstag\RequestHandler;

use Labstag\Event\ParagraphEntityEvent;
use Labstag\Lib\RequestHandlerLib;

class ParagraphRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        parent::handle($oldEntity, $entity);
        $this->dispatcher->dispatch(
            new ParagraphEntityEvent($oldEntity, $entity)
        );
    }
}
