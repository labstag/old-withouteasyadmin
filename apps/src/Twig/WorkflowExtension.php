<?php

namespace Labstag\Twig;

use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\ExtensionLib;

class WorkflowExtension extends ExtensionLib
{
    public function getFiltersFunctions(): array
    {
        return ['workflow_has' => 'workflowHas'];
    }

    public function workflowHas(EntityInterface $entity): bool
    {
        return $this->workflowService->has($entity);
    }
}
