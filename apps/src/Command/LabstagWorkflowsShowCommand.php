<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Workflow;
use Labstag\Repository\WorkflowRepository;
use Labstag\RequestHandler\WorkflowRequestHandler;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LabstagWorkflowsShowCommand extends Command
{

    protected static $defaultName = 'labstag:workflows-show';

    protected EventDispatcherInterface $dispatcher;

    protected WorkflowRepository $workflowRepository;

    protected EntityManagerInterface $entityManager;

    protected WorkflowRequestHandler $workflowRH;

    public function __construct(
        EventDispatcherInterface $dispatcher,
        EntityManagerInterface $entityManager,
        WorkflowRepository $workflowRepository,
        WorkflowRequestHandler $workflowRH
    )
    {
        parent::__construct();
        $this->workflowRH         = $workflowRH;
        $this->entityManager      = $entityManager;
        $this->workflowRepository = $workflowRepository;
        $this->dispatcher         = $dispatcher;
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
            if (0 != substr_count($name, 'state_machine')) {
                $workflows[$name] = $container->get($name);
            }
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

        $toDelete = $this->workflowRepository->toDelete($entities);
        foreach ($toDelete as $entity) {
            $this->entityManager->remove($entity);
        }

        $this->entityManager->flush();
        foreach ($data as $name => $transitions) {
            foreach ($transitions as $transition) {
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
}