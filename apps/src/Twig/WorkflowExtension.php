<?php

namespace Labstag\Twig;

use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\ExtensionLib;
use Twig\TwigFilter;
use Twig\TwigFunction;

class WorkflowExtension extends ExtensionLib
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('workflow_has', [$this, 'workflowHas']),
        ];
    }

    public function workflowHas(EntityInterface $entity): bool
    {
        return $this->workflowService->has($entity);
    }
}
