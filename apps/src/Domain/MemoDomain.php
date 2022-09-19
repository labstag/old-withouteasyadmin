<?php

namespace Labstag\Domain;

use Labstag\Entity\Memo;
use Labstag\Form\Admin\MemoType;
use Labstag\Form\Admin\Search\MemoType as SearchMemoType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\MemoRequestHandler;
use Labstag\Search\MemoSearch;

class MemoDomain extends DomainLib
{
    public function __construct(
        protected MemoRequestHandler $memoRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Memo::class;
    }

    public function getRequestHandler()
    {
        return $this->memoRequestHandler;
    }

    public function getSearchData()
    {
        return MemoSearch::class;
    }

    public function getSearchForm()
    {
        return SearchMemoType::class;
    }

    public function getType()
    {
        return MemoType::class;
    }
}
