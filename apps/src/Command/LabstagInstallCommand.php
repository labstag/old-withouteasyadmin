<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Lib\CommandLib;
use Labstag\Service\InstallService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LabstagInstallCommand extends CommandLib
{

    protected static $defaultName = 'labstag:install';

    public function __construct(
        protected array $serverenv,
        EntityManagerInterface $entityManager,
        protected InstallService $installService
    )
    {
        parent::__construct($entityManager);
    }

    protected function all($inputOutput): bool
    {
        $inputOutput->note('Installations');
        $executes = $this->getExecutesFunction();
        foreach ($executes as $function) {
            if ('all' != $function) {
                $this->{$function}($inputOutput);
            }
        }

        return true;
    }

    protected function configure()
    {
        $this->setDescription('Add a short description for your command');
        $this->addOption('layout', null, InputOption::VALUE_NONE, 'layout');
        $this->addOption('pages', null, InputOption::VALUE_NONE, 'pages');
        $this->addOption('menuadmin', null, InputOption::VALUE_NONE, 'menuadmin');
        $this->addOption('menuadminprofil', null, InputOption::VALUE_NONE, 'menuadminprofil');
        $this->addOption('group', null, InputOption::VALUE_NONE, 'group');
        $this->addOption('config', null, InputOption::VALUE_NONE, 'config');
        $this->addOption('templates', null, InputOption::VALUE_NONE, 'templates');
        $this->addOption('users', null, InputOption::VALUE_NONE, 'users');
        $this->addOption('all', null, InputOption::VALUE_NONE, 'all');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $options     = $input->getOptions();
        $executes    = $this->getExecutesFunction();
        foreach ($options as $option => $state) {
            $execute = $state ? $option : '';
            if (isset($executes[$execute])) {
                $this->{$executes[$execute]}($inputOutput);
            }
        }

        $inputOutput->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    protected function getExecutesFunction()
    {
        return [
            'layout'          => 'setLayouts',
            'pages'           => 'setPages',
            'menuadmin'       => 'setMenuAdmin',
            'menuadminprofil' => 'setMenuAdminProfil',
            'group'           => 'setGroup',
            'config'          => 'setConfig',
            'templates'       => 'setTemplates',
            'users'           => 'setUsers',
            'all'             => 'all',
        ];
    }

    protected function setConfig($inputOutput): bool
    {
        $inputOutput->note('Ajout de la configuration');
        $this->installService->config($this->serverenv);

        return true;
    }

    protected function setGroup($inputOutput): bool
    {
        $inputOutput->note('Ajout des groupes');
        $this->installService->group();

        return true;
    }

    protected function setLayouts($inputOutput): bool
    {
        $inputOutput->note('Ajout des layouts');
        $this->installService->layouts();

        return true;
    }

    protected function setMenuAdmin($inputOutput): bool
    {
        $inputOutput->note('Ajout du menu admin');
        $this->installService->menuadmin();

        return true;
    }

    protected function setMenuAdminProfil($inputOutput): bool
    {
        $inputOutput->note('Ajout du menu admin profil');
        $this->installService->menuadminprofil();

        return true;
    }

    protected function setPages($inputOutput): bool
    {
        $inputOutput->note('Ajout des pages');
        $this->installService->pages();

        return true;
    }

    protected function setTemplates($inputOutput): bool
    {
        $inputOutput->note('Ajout des templates');
        $this->installService->templates();

        return true;
    }

    protected function setUsers($inputOutput): bool
    {
        $inputOutput->note('Ajout des users');
        $this->installService->users();

        return true;
    }
}
