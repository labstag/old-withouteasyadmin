<?php

namespace Labstag\Lib;

use Twig\Environment;

abstract class TemplatePageLib
{

    protected Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }
}
