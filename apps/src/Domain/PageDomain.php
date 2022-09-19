<?php

namespace Labstag\Domain;

use Labstag\Entity\Page;
use Labstag\Form\Admin\PageType;
use Labstag\Form\Admin\Search\PageType as SearchPageType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\PageRequestHandler;
use Labstag\Search\PageSearch;

class PageDomain extends DomainLib
{
    public function __construct(
        protected PageRequestHandler $pageRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Page::class;
    }

    public function getRequestHandler()
    {
        return $this->pageRequestHandler;
    }

    public function getSearchData()
    {
        return PageSearch::class;
    }

    public function getSearchForm()
    {
        return SearchPageType::class;
    }

    public function getType()
    {
        return PageType::class;
    }
}
