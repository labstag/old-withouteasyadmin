<?php

namespace Labstag\Command;

use Labstag\Service\GeocodeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LabstagGeocodeInstallCommand extends Command
{

    protected static $defaultName = 'labstag:geocode:install';

    protected GeocodeService $service;

    public function __construct(GeocodeService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Récupération des géocodes');
        $this->addArgument('country', InputArgument::REQUIRED, 'code pays');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->title('Récupération des code postaux');
        $country = $input->getArgument('country');
        if (empty($country)) {
            $inputOutput->note(
                sprintf(
                    'Argument countrie obligatoire: %s',
                    $country
                )
            );

            return COMMAND::FAILURE;
        }

        $csv = $this->service->csv($country);
        if (0 == $csv) {
            $inputOutput->warning(
                ['fichier inexistant']
            );

            return COMMAND::FAILURE;
        }

        $progressBar = new ProgressBar($output, count($csv));
        $table       = $this->service->tables($csv);
        $progressBar->start();
        foreach ($table as $row) {
            $this->service->add($row);
            $progressBar->advance();
        }

        $progressBar->finish();
        $inputOutput->newLine();
        $inputOutput->success("Fin d'enregistrement");

        return Command::SUCCESS;
    }
}