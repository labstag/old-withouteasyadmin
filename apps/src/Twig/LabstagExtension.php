<?php

namespace Labstag\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class LabstagExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('formClass', [$this, 'formClass']),
            new TwigFilter('formPrototype', [$this, 'formPrototype']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('formClass', [$this, 'formClass']),
            new TwigFunction('formPrototype', [$this, 'formPrototype']),
        ];
    }

    public function formPrototype(array $blockPrefixes): string
    {
        $file = '';
        if ($blockPrefixes[1] != 'collection_entry') {
            return $file;
        }

        $type = $blockPrefixes[2];

        $newFile = 'prototype/'.$type.'.html.twig';
        if (is_file(__DIR__.'/../../templates/'.$newFile)) {
            $file = $newFile;
        }

        return $file;
    }

    public function formClass($class)
    {
        $file = '';

        if (is_null($class['data'])) {
            return $file;
        }

        if (!is_object($class['data'])) {
            return $file;
        }

        $tabClass = explode('\\', get_class($class['data']));
        $type     = end($tabClass);

        $newFile = 'forms/'.$type.'.html.twig';
        if (is_file(__DIR__.'/../../templates/'.$newFile)) {
            $file = $newFile;
        }

        return $file;

    }
}
