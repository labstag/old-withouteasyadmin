<?php

namespace Labstag\Command;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Lib\CommandLib;
use Labstag\Service\GuardService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LabstagGuardRouteCommand extends CommandLib
{

    /**
     * @var string
     */
    protected static $defaultName = 'labstag:guard-route';

    public function __construct(
        EntityManagerInterface $entityManager,
        protected GuardService $guardService
    )
    {
        parent::__construct($entityManager);
    }

    protected function configure(): void
    {
        $this->setDescription('Enregistre les routes pour le système de GUARD');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);
        $symfonyStyle->title('Installation du système de droit utilisateurs');
        $symfonyStyle->section('Enregistrement des routes');

        $all = $this->guardService->all();
        $progressBar = new ProgressBar($output, is_countable($all) ? count($all) : 0);
        $progressBar->start();
        foreach (array_keys($all) as $name) {
            $progressBar->advance();
            $this->guardService->save($name);
        }

        $progressBar->finish();
        $symfonyStyle->newLine();
        $symfonyStyle->success("Fin d'enregistrement");

        $table = $this->guardService->tables();
        $symfonyStyle->table(
            [
                'route',
                'controller',
            ],
            $table
        );
        $table = $this->guardService->old();
        if (0 != (is_countable($table) ? count($table) : 0)) {
            $symfonyStyle->section('Suppression des anciennes routes');
            $symfonyStyle->table(
                ['route'],
                $table
            );
            $table = $this->guardService->delete();
            $symfonyStyle->newLine();
            $symfonyStyle->success('Fin de suppression');
        }

        return Command::SUCCESS;
    }
}
