<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Lib\CommandLib;
use Labstag\Service\GeocodeService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LabstagGeocodeInstallCommand extends CommandLib
{

    /**
     * @var string
     */
    protected static $defaultName = 'labstag:geocode:install';

    public function __construct(
        EntityManagerInterface $entityManager,
        protected GeocodeService $geocodeService
    )
    {
        parent::__construct($entityManager);
    }

    protected function configure(): void
    {
        $this->setDescription('Récupération des géocodes');
        $this->addArgument('country', InputArgument::REQUIRED, 'code pays');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('Récupération des code postaux');

        $country = $input->getArgument('country');
        if (empty($country)) {
            $symfonyStyle->note(
                sprintf(
                    'Argument countrie obligatoire: %s',
                    $country
                )
            );

            return COMMAND::FAILURE;
        }

        $csv = $this->geocodeService->csv($country);
        if (0 == $csv) {
            $symfonyStyle->warning(
                ['fichier inexistant']
            );

            return COMMAND::FAILURE;
        }

        $progressBar = new ProgressBar($output, is_countable($csv) ? count($csv) : 0);
        $table = $this->geocodeService->tables($csv);
        $progressBar->start();
        foreach ($table as $row) {
            $this->geocodeService->add($row);
            $progressBar->advance();
        }

        $progressBar->finish();
        $symfonyStyle->newLine();
        $symfonyStyle->success("Fin d'enregistrement");

        return Command::SUCCESS;
    }
}
