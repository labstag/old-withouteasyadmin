<?php
namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\Registry;

abstract class RequestHandlerLib
{

    protected EntityManagerInterface $entityManager;

    protected Registry $workflows;

    protected EventDispatcherInterface $dispatcher;

    public function __construct(
        EntityManagerInterface $entityManager,
        Registry $workflows,
        EventDispatcherInterface $dispatcher
    )
    {
        $this->entityManager = $entityManager;
        $this->workflows     = $workflows;
        $this->dispatcher    = $dispatcher;
    }

    protected function initWorkflow($entity)
    {
        if (!$this->workflows->has($entity)) {
            return;
        }

        $workflow    = $this->workflows->get($entity);
        $definition  = $workflow->getDefinition();
        $transitions = $definition->getTransitions();
        foreach ($transitions as $transition) {
            $name = $transition->getName();
            if ($workflow->can($entity, $name)) {
                $workflow->apply($entity, $name);
                break;
            }
        }
    }

    public function create($oldEntity, $entity)
    {
        $this->initWorkflow($entity);
        unset($oldEntity);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function update($oldEntity, $entity)
    {
        unset($oldEntity, $entity);
        $this->entityManager->flush();
    }

    public function changeWorkflowState($entity, $state)
    {
        if (!$this->workflows->has($entity)) {
            return;
        }

        $entity->setState($state);
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
}
