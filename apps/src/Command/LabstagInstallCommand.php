<?php

namespace Labstag\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class LabstagInstallCommand extends Command
{

    protected static $defaultName = 'labstag:install';

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
            $this->menuadmin($input, $output);
        } elseif ($input->getOption('menuadminprofil')) {
            $this->menuadminprofil($input, $output);
        } elseif ($input->getOption('group')) {
            $this->group($input, $output);
        } elseif ($input->getOption('config')) {
            $this->config($input, $output);
        } elseif ($input->getOption('templates')) {
            $this->templates($input, $output);
        } elseif ($input->getOption('all')) {
            $this->all($input, $output);
        }

        $inputOutput->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }

    protected function menuadmin($input, $output)
    {
        $data = $this->getData('menuadmin');
        dump($data);
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->note('Ajout du menu admin');
    }

    protected function menuadminprofil($input, $output)
    {
        $data = $this->getData('menuadminprofil');
        dump($data);
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->note('Ajout du menu admin profil');
    }

    protected function group($input, $output)
    {
        $data = $this->getData('group');
        dump($data);
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->note('Ajout des groupes');
    }

    protected function config($input, $output)
    {
        $data = $this->getData('config');
        dump($data);
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->note('Ajout de la configuration');
    }

    protected function templates($input, $output)
    {
        $data = $this->getData('template');
        dump($data);
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->note('Ajout des templates');
    }

    protected function getData($file)
    {
        $file = __DIR__.'/../../json/'.$file.'.json';
        $data = [];
        if (is_file($file)) {
            $data = json_decode(file_get_contents($file), true);
        }

        return $data;
    }

    public function all($input, $output)
    {
        $inputOutput = new SymfonyStyle($input, $output);
        $inputOutput->note('Installations');
        $this->menuadmin($input, $output);
        $this->menuadminprofil($input, $output);
        $this->group($input, $output);
        $this->config($input, $output);
        $this->templates($input, $output);
    }
}
