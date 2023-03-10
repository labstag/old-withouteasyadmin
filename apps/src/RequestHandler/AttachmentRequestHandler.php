<?php

namespace Labstag\RequestHandler;

use Labstag\Lib\RequestHandlerLib;
use Symfony\Component\Workflow\WorkflowInterface;

class AttachmentRequestHandler extends RequestHandlerLib
{
    public function handle(mixed $oldEntity, mixed $entity): void
    {
        parent::handle($oldEntity, $entity);

        $oldFile = $oldEntity->getName();
        if ('' != $oldFile && is_file($oldFile)) {
            unlink($oldFile);
        }

        /** @var WorkflowInterface $workflow */
        $workflow = $this->workflowService->get($entity);
        if ($workflow->can($entity, 'reenvoyer')) {
            $workflow->apply($entity, 'reenvoyer');
        }
    }
}
