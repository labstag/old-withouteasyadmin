<?php

namespace Labstag\RequestHandler;

use Labstag\Lib\RequestHandlerLib;

class AttachmentRequestHandler extends RequestHandlerLib
{
    public function handle($oldEntity, $entity)
    {
        parent::handle($oldEntity, $entity);

        $oldFile = $oldEntity->getName();
        if ('' != $oldFile && is_file($oldFile)) {
            unlink($oldFile);
        }

        $workflow = $this->workflows->get($entity);
        if ($workflow->can($entity, 'reenvoyer')) {
            $workflow->apply($entity, 'reenvoyer');
        }
    }
}
