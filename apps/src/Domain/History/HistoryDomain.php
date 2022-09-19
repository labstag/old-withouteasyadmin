<?php

namespace Labstag\Domain\History;

use Labstag\Entity\History;
use Labstag\Form\Admin\HistoryType;
use Labstag\Form\Admin\Search\HistoryType as SearchHistoryType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\HistoryRequestHandler;
use Labstag\Search\HistorySearch;

class HistoryDomain extends DomainLib
{
    public function __construct(
        protected HistoryRequestHandler $historyRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return History::class;
    }

    public function getRequestHandler()
    {
        return $this->historyRequestHandler;
    }

    public function getSearchData()
    {
        return HistorySearch::class;
    }

    public function getSearchForm()
    {
        return SearchHistoryType::class;
    }

    public function getType()
    {
        return HistoryType::class;
    }
}
