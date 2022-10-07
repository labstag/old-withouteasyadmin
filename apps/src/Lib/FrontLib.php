<?php

namespace Labstag\Lib;

use Labstag\Repository\PageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

abstract class FrontLib
{

    protected Request $request;

    public function __construct(
        protected PageRepository $pageRepository,
        protected RequestStack $requestStack,
        protected RouterInterface $router
    )
    {
        $this->request = $requestStack->getCurrentRequest();
    }
}
