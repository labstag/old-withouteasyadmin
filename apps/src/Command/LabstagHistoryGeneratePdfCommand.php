<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\History;
use Labstag\Lib\CommandLib;
use Labstag\Repository\HistoryRepository;
use Labstag\Service\HistoryService;
use Labstag\Service\RepositoryService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(
    name: 'labstag:history:generate-pdf'
)]
class LabstagHistoryGeneratePdfCommand extends CommandLib
{
    public function __construct(
        RepositoryService $repositoryService,
        EntityManagerInterface $entityManager,
        protected ParameterBagInterface $parameterBag,
        protected HistoryService $historyService,
        protected HistoryRepository $historyRepository
    )
    {
        parent::__construct($repositoryService, $entityManager);
    }

    protected function configure(): void
    {
        $this->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $histories = $this->historyRepository->findAll();
        $symfonyStyle->title('Génération des PDF');
        $symfonyStyle->progressStart(is_countable($histories) ? count($histories) : 0);

        $fileDirectory = (string) $this->parameterBag->get('file_directory');
        /** @var History $history */
        foreach ($histories as $history) {
            $this->historyService->process(
                $fileDirectory,
                $history->getId(),
                true
            );
            $this->historyService->process(
                $fileDirectory,
                $history->getId(),
                false
            );
            $symfonyStyle->progressAdvance();
        }

        $symfonyStyle->progressFinish();

        return Command::SUCCESS;
    }
}
