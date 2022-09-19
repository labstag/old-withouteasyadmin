<?php

namespace Labstag\Domain;

use Labstag\Entity\Category;
use Labstag\Form\Admin\CategoryType;
use Labstag\Form\Admin\Search\CategoryType as SearchCategoryType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\CategoryRequestHandler;
use Labstag\Search\CategorySearch;

class CategoryDomain extends DomainLib
{
    public function __construct(
        protected CategoryRequestHandler $categoryRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Category::class;
    }

    public function getRequestHandler()
    {
        return $this->categoryRequestHandler;
    }

    public function getSearchData()
    {
        return CategorySearch::class;
    }

    public function getSearchForm()
    {
        return SearchCategoryType::class;
    }

    public function getType()
    {
        return CategoryType::class;
    }
}
