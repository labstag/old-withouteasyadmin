<?php

namespace Labstag\Command;

use Labstag\Lib\CommandLib;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LabstagUpdateCommand extends CommandLib
{

    protected static $defaultName = 'labstag:update';

    protected function configure()
    {
        $this->setDescription('Add a short description for your command');
        $this->addOption('maintenanceon', null, InputOption::VALUE_NONE, 'Enable maintenance');
        $this->addOption('maintenanceoff', null, InputOption::VALUE_NONE, 'Disable maintenance');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput     = new SymfonyStyle($input, $output);
        $publicIndex     = 'public/index.php';
        $maintenanceFile = 'maintenance.html';
        $actifFile       = 'public.php';
        if (!is_file($publicIndex)) {
            return Command::FAILURE;
        }

        if ($input->getOption('maintenanceon') && is_file($maintenanceFile)) {
            $maintanceFile = file_get_contents($maintenanceFile);
            file_put_contents($publicIndex, $maintanceFile);
            $inputOutput->note('Maintenance activé');
        }

        if ($input->getOption('maintenanceoff') && is_file($actifFile)) {
            $publicFile = file_get_contents($actifFile);
            file_put_contents($publicIndex, $publicFile);
            $inputOutput->note('Maintenance désactivé');
        }

        $inputOutput->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
