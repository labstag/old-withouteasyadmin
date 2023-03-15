<?php

namespace Labstag\RequestHandler;

use Labstag\Entity\Attachment;
use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\RequestHandlerLib;
use Symfony\Component\Workflow\WorkflowInterface;

class AttachmentRequestHandler extends RequestHandlerLib
{
    public function handle(EntityInterface $oldEntity, EntityInterface $entity): void
    {
        parent::handle($oldEntity, $entity);
        if (!$oldEntity instanceof Attachment || !$entity instanceof Attachment) {
            return;
        }

        $name = $oldEntity->getName();
        if (!is_string($name)) {
            return;
        }

        if ('' != $name && is_file($name)) {
            unlink($name);
        }

        /** @var WorkflowInterface $workflow */
        $workflow = $this->workflowService->get($entity);
        $code     = 'reenvoyer';
        if ($workflow->can($entity, $code)) {
            $workflow->apply($entity, $code);
        }
    }
}
