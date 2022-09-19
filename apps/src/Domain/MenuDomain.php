<?php

namespace Labstag\Domain;

use Labstag\Entity\Menu;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\MenuRequestHandler;

class MenuDomain extends DomainLib
{
    public function __construct(
        protected MenuRequestHandler $menuRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Menu::class;
    }

    public function getRequestHandler()
    {
        return $this->menuRequestHandler;
    }
}
