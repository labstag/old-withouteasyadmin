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

    protected static $defaultName = 'labstag:guard-route';

    public function __construct(
        EntityManagerInterface $entityManager,
        protected GuardService $service
    )
    {
        parent::__construct($entityManager);
    }

    protected function configure()
    {
        $this->setDescription('Enregistre les routes pour le système de GUARD');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->title('Installation du système de droit utilisateurs');
        $inputOutput->section('Enregistrement des routes');

        $all         = $this->service->all();
        $progressBar = new ProgressBar($output, count($all));
        $progressBar->start();
        foreach (array_keys($all) as $name) {
            $progressBar->advance();
            $this->service->save($name);
        }

        $progressBar->finish();
        $inputOutput->newLine();
        $inputOutput->success("Fin d'enregistrement");

        $table = $this->service->tables();
        $inputOutput->table(
            [
                'route',
                'controller',
            ],
            $table
        );
        $table = $this->service->old();
        if (0 != (is_countable($table) ? count($table) : 0)) {
            $inputOutput->section('Suppression des anciennes routes');
            $inputOutput->table(
                ['route'],
                $table
            );
            $table = $this->service->delete();
            $inputOutput->newLine();
            $inputOutput->success('Fin de suppression');
        }

        return Command::SUCCESS;
    }
}
