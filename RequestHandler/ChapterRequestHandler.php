<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Chapter;
use Labstag\Event\ChapterEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class ChapterRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Chapter || !$entity instanceof Chapter) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new ChapterEntityEvent($oldEntity, $entity)
        );
    }
}
