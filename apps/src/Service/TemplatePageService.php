<?php

namespace Labstag\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

class TemplatePageService
{

    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getAll(string $namespace): array
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../TemplatePage')->name('*.php');
        $plugins = [];
        foreach ($finder as $file) {
            $plugins[] = rtrim($namespace, '\\').'\\'.$file->getFilenameWithoutExtension();
        }

        return $plugins ?? [];
    }

    public function getChoices()
    {
        $namespace = 'Labstag\TemplatePage';
        $finder    = new Finder();
        $finder->files()->in(__DIR__.'/../TemplatePage')->name('*.php');
        $plugins = [];
        foreach ($finder as $file) {
            $class = rtrim($namespace, '\\').'\\'.$file->getFilenameWithoutExtension();

            $plugins[$class] = $class;
        }

        return $plugins;
    }

    public function getClass($class)
    {
        return $this->container->get($class);
    }
}
