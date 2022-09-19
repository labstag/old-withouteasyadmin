<?php

namespace Labstag\Domain\History;

use Labstag\Entity\Chapter;
use Labstag\Form\Admin\ChapterType;
use Labstag\Form\Admin\Search\ChapterType as SearchChapterType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\ChapterRequestHandler;
use Labstag\Search\ChapterSearch;

class ChapterDomain extends DomainLib
{
    public function __construct(
        protected ChapterRequestHandler $chapterRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Chapter::class;
    }

    public function getRequestHandler()
    {
        return $this->chapterRequestHandler;
    }

    public function getSearchData()
    {
        return ChapterSearch::class;
    }

    public function getSearchForm()
    {
        return SearchChapterType::class;
    }

    public function getType()
    {
        return ChapterType::class;
    }
}
