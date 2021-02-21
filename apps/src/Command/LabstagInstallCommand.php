<?php

namespace Labstag\Command;

use Labstag\Service\InstallService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LabstagInstallCommand extends Command
{

    protected static $defaultName = 'labstag:install';

    protected InstallService $installService;

    public function __construct(
        InstallService $installService
    )
    {
        $this->installService = $installService;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Add a short description for your command');
        $this->addOption('menuadmin', null, InputOption::VALUE_NONE, 'menuadmin');
        $this->addOption('menuadminprofil', null, InputOption::VALUE_NONE, 'menuadminprofil');
        $this->addOption('group', null, InputOption::VALUE_NONE, 'group');
        $this->addOption('config', null, InputOption::VALUE_NONE, 'config');
        $this->addOption('templates', null, InputOption::VALUE_NONE, 'templates');
        $this->addOption('all', null, InputOption::VALUE_NONE, 'all');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        if ($input->getOption('menuadmin')) {
            $inputOutput->note('Ajout du menu admin');
            $this->installService->menuadmin();
        } elseif ($input->getOption('menuadminprofil')) {
            $inputOutput->note('Ajout du menu admin profil');
            $this->installService->menuadminprofil();
        } elseif ($input->getOption('group')) {
            $inputOutput->note('Ajout des groupes');
            $this->installService->group();
        } elseif ($input->getOption('config')) {
            $inputOutput->note('Ajout de la configuration');
            $this->installService->config();
        } elseif ($input->getOption('templates')) {
            $inputOutput->note('Ajout des templates');
            $this->installService->templates();
        } elseif ($input->getOption('all')) {
            $inputOutput->note('Installations');
            $this->installService->all();
        }

        $inputOutput->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
