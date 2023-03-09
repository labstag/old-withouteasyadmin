<?php

namespace Labstag\Command;

use Labstag\Lib\CommandLib;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'labstag:update')]
class LabstagUpdateCommand extends CommandLib
{
    protected function configure(): void
    {
        $this->setDescription('Add a short description for your command');
        $this->addOption(
            name: 'maintenanceon',
            mode: InputOption::VALUE_NONE,
            description: 'Enable maintenance'
        );
        $this->addOption(
            name: 'maintenanceoff',
            mode: InputOption::VALUE_NONE,
            description: 'Disable maintenance'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle    = new SymfonyStyle($input, $output);
        $publicIndex     = 'public/index.php';
        $maintenanceFile = 'maintenance.html';
        $actifFile       = 'public.php';
        if (!is_file($publicIndex)) {
            return Command::FAILURE;
        }

        if ($input->getOption('maintenanceon') && is_file($maintenanceFile)) {
            $maintanceFile = file_get_contents($maintenanceFile);
            file_put_contents($publicIndex, $maintanceFile);
            $symfonyStyle->note('Maintenance activé');
        }

        if ($input->getOption('maintenanceoff') && is_file($actifFile)) {
            $publicFile = file_get_contents($actifFile);
            file_put_contents($publicIndex, $publicFile);
            $symfonyStyle->note('Maintenance désactivé');
        }

        $symfonyStyle->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
