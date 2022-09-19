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

    /**
     * @var string
     */
    protected static $defaultName = 'labstag:install';

    public function __construct(
        protected array $serverenv,
        EntityManagerInterface $entityManager,
        protected InstallService $installService
    )
    {
        parent::__construct($entityManager);
    }

    protected function all(SymfonyStyle $symfonyStyle): bool
    {
        $symfonyStyle->note('Installations');
        $executes = $this->getExecutesFunction();
        foreach ($executes as $execute) {
            if ('all' != $execute) {
                call_user_func([$this, $execute], $symfonyStyle);
            }
        }

        return true;
    }

    protected function configure(): void
    {
        $this->setDescription('Add a short description for your command');
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
        $symfonyStyle = new SymfonyStyle($input, $output);
        $options      = $input->getOptions();
        $executes     = $this->getExecutesFunction();
        foreach ($options as $option => $state) {
            $execute = $state ? $option : '';
            if (isset($executes[$execute])) {
                call_user_func([$this, $executes[$execute]], $symfonyStyle);
            }
        }

        $symfonyStyle->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    protected function getExecutesFunction(): array
    {
        return [
            'menuadmin'       => 'setMenuAdmin',
            'menuadminprofil' => 'setMenuAdminProfil',
            'group'           => 'setGroup',
            'config'          => 'setConfig',
            'templates'       => 'setTemplates',
            'users'           => 'setUsers',
            'all'             => 'all',
        ];
    }

    protected function setConfig(SymfonyStyle $symfonyStyle): bool
    {
        $symfonyStyle->note('Ajout de la configuration');
        $this->installService->config($this->serverenv);

        return true;
    }

    protected function setGroup(SymfonyStyle $symfonyStyle): bool
    {
        $symfonyStyle->note('Ajout des groupes');
        $this->installService->group();

        return true;
    }

    protected function setMenuAdmin(SymfonyStyle $symfonyStyle): bool
    {
        $symfonyStyle->note('Ajout du menu admin');
        $this->installService->menuadmin();

        return true;
    }

    protected function setMenuAdminProfil(SymfonyStyle $symfonyStyle): bool
    {
        $symfonyStyle->note('Ajout du menu admin profil');
        $this->installService->menuadminprofil();

        return true;
    }

    protected function setTemplates(SymfonyStyle $symfonyStyle): bool
    {
        $symfonyStyle->note('Ajout des templates');
        $this->installService->templates();

        return true;
    }

    protected function setUsers(SymfonyStyle $symfonyStyle): bool
    {
        $symfonyStyle->note('Ajout des users');
        $this->installService->users();

        return true;
    }
}
