<?php

namespace Labstag\Domain;

use Labstag\Entity\Template;
use Labstag\Form\Admin\Search\TemplateType as SearchTemplateType;
use Labstag\Form\Admin\TemplateType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\Search\TemplateSearch;

class TemplateDomain extends DomainLib
{
    public function __construct(
        protected TemplateRequestHandler $templateRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Template::class;
    }

    public function getRequestHandler()
    {
        return $this->templateRequestHandler;
    }

    public function getSearchData()
    {
        return TemplateSearch::class;
    }

    public function getSearchForm()
    {
        return SearchTemplateType::class;
    }

    public function getType()
    {
        return TemplateType::class;
    }
}
