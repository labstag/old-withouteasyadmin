<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Lib\CommandLib;
use Labstag\Repository\HistoryRepository;
use Labstag\Service\HistoryService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'labstag:history:generate-pdf',
    description: 'Add a short description for your command',
)]
class LabstagHistoryGeneratePdfCommand extends CommandLib
{
    public function __construct(
        EntityManagerInterface $entityManager,
        protected ParameterBagInterface $parameterBag,
        protected HistoryService $historyService,
        protected HistoryRepository $historyRepository
    )
    {
        parent::__construct($entityManager);
    }

    protected function configure(): void
    {
        $this->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $histories    = $this->historyRepository->findAll();
        $symfonyStyle->title('Génération des PDF');
        $symfonyStyle->progressStart(is_countable($histories) ? count($histories) : 0);
        foreach ($histories as $history) {
            $this->historyService->process(
                $this->getParameter('file_directory'),
                $history->getId(),
                true
            );
            $this->historyService->process(
                $this->getParameter('file_directory'),
                $history->getId(),
                false
            );
            $symfonyStyle->progressAdvance();
        }

        $symfonyStyle->progressFinish();

        return Command::SUCCESS;
    }

    protected function getParameter(string $name)
    {
        return $this->parameterBag->get($name);
    }
}
