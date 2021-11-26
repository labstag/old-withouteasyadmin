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
            $className = rtrim($namespace, '\\').'\\'.$file->getFilenameWithoutExtension();
            if (class_exists($className)) {
                $plugins[] = [
                    'name'    => $className,
                    'methods' => get_class_methods($className),
                ];
            }
        }

        return $plugins ?? [];
    }

    public function getChoices()
    {
        $namespace = 'Labstag\TemplatePage';
        $files     = $this->getAll($namespace);
        $choices   = [];
        foreach ($files as $row) {
            $name = $row['name'];
            foreach ($row['methods'] as $key) {
                if ('__construct' == $key) {
                    continue;
                }

                $code                                             = $name.'::'.$key;
                $choices[str_replace($namespace.'\\', '', $code)] = $code;
            }
        }

        return $choices;
    }

    public function getClass($class)
    {
        return $this->container->get($class);
    }
}
