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
            new TwigFilter('formName', [$this, 'formName']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('formName', [$this, 'formName']),
        ];
    }

    public function formName($value)
    {
        // dump(get_class($value));
        dump($value);
    }
}
