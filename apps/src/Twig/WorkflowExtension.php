<?php

namespace Labstag\Twig;

use Labstag\Interfaces\EntityInterface;
use Labstag\Lib\ExtensionLib;
use Labstag\Service\WorkflowService;
use Twig\Environment;

class WorkflowExtension extends ExtensionLib
{
    public function __construct(
        protected Environment $twigEnvironment,
        protected WorkflowService $workflowService
    )
    {
        parent::__construct($twigEnvironment);
    }

    public function getFiltersFunctions(): array
    {
        return ['workflow_has' => 'workflowHas'];
    }

    public function workflowHas(EntityInterface $entity): bool
    {
        return $this->workflowService->has($entity);
    }
}
