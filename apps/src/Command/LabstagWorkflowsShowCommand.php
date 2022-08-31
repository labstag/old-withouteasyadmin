<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Workflow;
use Labstag\Lib\CommandLib;
use Labstag\Repository\WorkflowRepository;
use Labstag\RequestHandler\WorkflowRequestHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LabstagWorkflowsShowCommand extends CommandLib
{

    protected static $defaultName = 'labstag:workflows-show';

    public function __construct(
        EntityManagerInterface $entityManager,
        protected EventDispatcherInterface $dispatcher,
        protected WorkflowRequestHandler $workflowRH,
        protected WorkflowRepository $workflowRepo
    )
    {
        parent::__construct($entityManager);
    }

    protected function configure()
    {
        $this->setDescription('Ajout des workflows en base de données');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->title('Ajout des workflows dans la base de données');
        $container = $this->getApplication()->getKernel()->getContainer();
        $list      = $container->getServiceIds();
        $workflows = [];
        foreach ($list as $name) {
            if (0 == substr_count((string) $name, 'state_machine')) {
                continue;
            }

            $workflows[$name] = $container->get($name);
        }

        $data     = [];
        $entities = [];
        foreach ($workflows as $name => $workflow) {
            $definition  = $workflow->getDefinition();
            $name        = $workflow->getName();
            $entities[]  = $name;
            $data[$name] = [];
            $transitions = $definition->getTransitions();
            foreach ($transitions as $transition) {
                $data[$name][] = $transition->getName();
            }
        }

        $this->delete($entities, $data);
        foreach ($data as $name => $transitions) {
            foreach ($transitions as $transition) {
                $workflow = $this->workflowRepo->findOneBy(
                    [
                        'entity'     => $name,
                        'transition' => $transition,
                    ]
                );
                if ($workflow instanceof Workflow) {
                    continue;
                }

                $workflow = new Workflow();
                $workflow->setEntity($name);
                $workflow->setTransition($transition);
                $old = clone $workflow;
                $this->workflowRH->handle($old, $workflow);
            }
        }

        $inputOutput->success('Fin de traitement');

        return Command::SUCCESS;
    }

    private function delete($entities, $data)
    {
        $toDelete = $this->workflowRepo->toDeleteEntities($entities);
        foreach ($toDelete as $entity) {
            $this->workflowRepo->remove($entity);
        }

        foreach ($data as $entity => $transitions) {
            $toDelete = $this->workflowRepo->toDeleteTransition($entity, $transitions);
            foreach ($toDelete as $entity) {
                $this->workflowRepo->remove($entity);
            }
        }
    }
}
