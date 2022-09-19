<?php

namespace Labstag\Domain;

use Labstag\Entity\Render;
use Labstag\Form\Admin\RenderType;
use Labstag\Form\Admin\Search\RenderType as SearchRenderType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\RenderRequestHandler;
use Labstag\Search\RenderSearch;

class RenderDomain extends DomainLib
{
    public function __construct(
        protected RenderRequestHandler $renderRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Render::class;
    }

    public function getRequestHandler()
    {
        return $this->renderRequestHandler;
    }

    public function getSearchData()
    {
        return RenderSearch::class;
    }

    public function getSearchForm()
    {
        return SearchRenderType::class;
    }

    public function getType()
    {
        return RenderType::class;
    }
}
