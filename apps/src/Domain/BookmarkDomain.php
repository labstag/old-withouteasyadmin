<?php

namespace Labstag\Domain;

use Labstag\Entity\Bookmark;
use Labstag\Form\Admin\Bookmark\PrincipalType;
use Labstag\Form\Admin\Search\BookmarkType as SearchBookmarkType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\BookmarkRequestHandler;
use Labstag\Search\BookmarkSearch;

class BookmarkDomain extends DomainLib
{
    public function __construct(
        protected BookmarkRequestHandler $bookmarkRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Bookmark::class;
    }

    public function getRequestHandler()
    {
        return $this->bookmarkRequestHandler;
    }

    public function getSearchData()
    {
        return BookmarkSearch::class;
    }

    public function getSearchForm()
    {
        return SearchBookmarkType::class;
    }

    public function getType()
    {
        return PrincipalType::class;
    }
}
