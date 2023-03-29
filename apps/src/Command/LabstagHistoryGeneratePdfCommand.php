<?php

namespace Labstag\Command;

use Labstag\Entity\History;
use Labstag\Lib\CommandLib;
use Labstag\Repository\HistoryRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'labstag:history:generate-pdf'
)]
class LabstagHistoryGeneratePdfCommand extends CommandLib
{
    protected function configure(): void
    {
        $this->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        /** @var HistoryRepository $repositoryLib */
        $repositoryLib = $this->repositoryService->get(History::class);
        $histories     = $repositoryLib->findAll();
        $symfonyStyle->title('Génération des PDF');
        $symfonyStyle->progressStart(is_countable($histories) ? count($histories) : 0);

        $fileDirectory = $this->parameterBag->get('file_directory');
        if (!is_string($fileDirectory)) {
            $symfonyStyle->progressFinish();

            return Command::SUCCESS;
        }

        /** @var History $history */
        foreach ($histories as $history) {
            $this->historyService->process(
                (string) $fileDirectory,
                (string) $history->getId(),
                true
            );
            $this->historyService->process(
                (string) $fileDirectory,
                (string) $history->getId(),
                false
            );
            $symfonyStyle->progressAdvance();
        }

        $symfonyStyle->progressFinish();

        return Command::SUCCESS;
    }
}
