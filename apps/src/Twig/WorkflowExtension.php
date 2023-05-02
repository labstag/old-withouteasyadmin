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
        $dataFilters = $this->getFiltersFunctions();
        $filters     = [];
        foreach ($dataFilters as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $filters[] = new TwigFilter($key, $callable);
        }

        return $filters;
    }

    public function getFiltersFunctions(): array
    {
        return ['workflow_has' => 'workflowHas'];
    }

    /**
     * @return TwigFunction[]
     */
    public function getFunctions(): array
    {
        $dataFunctions = $this->getFiltersFunctions();
        $functions     = [];
        foreach ($dataFunctions as $key => $function) {
            /** @var callable $callable */
            $callable = [
                $this,
                $function,
            ];
            $functions[] = new TwigFunction($key, $callable);
        }

        return $functions;
    }

    public function workflowHas(EntityInterface $entity): bool
    {
        return $this->workflowService->has($entity);
    }
}
