<?php

namespace Labstag\Service;

use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

class WorkflowService
{
    // Symfony will inject the 'blog_publishing' workflow configured before
    public function __construct(
        protected Registry $registry
    )
    {
    }

    public function has($entity): bool
    {
        return $this->registry->has($entity);
    }

    public function get($entity): Workflow
    {
        return $this->registry->get($entity);
    }
}