<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Lib\CommandLib;
use Labstag\Service\InstallService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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

    protected function configure(): void
    {
        $this->setDescription('Add a short description for your command');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->note('Ajout de la configuration');

        $this->installService->config($this->serverenv);
        $symfonyStyle->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
