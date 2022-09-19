<?php

namespace Labstag\Domain;

use Labstag\Entity\Layout;
use Labstag\Form\Admin\LayoutType;
use Labstag\Form\Admin\Search\LayoutType as SearchLayoutType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\LayoutRequestHandler;
use Labstag\Search\LayoutSearch;

class LayoutDomain extends DomainLib
{
    public function __construct(
        protected LayoutRequestHandler $layoutRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Layout::class;
    }

    public function getRequestHandler()
    {
        return $this->layoutRequestHandler;
    }

    public function getSearchData()
    {
        return LayoutSearch::class;
    }

    public function getSearchForm()
    {
        return SearchLayoutType::class;
    }

    public function getType()
    {
        return LayoutType::class;
    }
}
