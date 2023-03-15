<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Paragraph;
use Labstag\Event\ParagraphEntityEvent;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;

class ParagraphRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Paragraph || !$entity instanceof Paragraph) {
            return;
        }

        $this->eventDispatcher->dispatch(
            new ParagraphEntityEvent($oldEntity, $entity)
        );
    }
}
