<?php

namespace Labstag\Command;

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
class LabstagHistoryGeneratePdfCommand extends Command
{

    protected ParameterBagInterface $containerBag;

    protected HistoryRepository $historyRepository;

    protected HistoryService $historyService;

    public function __construct(
        ParameterBagInterface $containerBag,
        HistoryRepository $historyRepository,
        HistoryService $historyService
    )
    {
        $this->containerBag      = $containerBag;
        $this->historyRepository = $historyRepository;
        $this->historyService    = $historyService;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inoutput  = new SymfonyStyle($input, $output);
        $histories = $this->historyRepository->findAll();
        $inoutput->title('Génération des PDF');
        $inoutput->progressStart(count($histories));
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
            $inoutput->progressAdvance();
        }

        $inoutput->progressFinish();

        return Command::SUCCESS;
    }

    protected function getParameter(string $name)
    {
        return $this->containerBag->get($name);
    }
}
