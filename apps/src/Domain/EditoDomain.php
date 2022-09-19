<?php

namespace Labstag\Domain;

use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Form\Admin\Search\EditoType as SearchEditoType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\Search\EditoSearch;

class EditoDomain extends DomainLib
{
    public function __construct(
        protected EditoRequestHandler $editoRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Edito::class;
    }

    public function getRequestHandler()
    {
        return $this->editoRequestHandler;
    }

    public function getSearchData()
    {
        return EditoSearch::class;
    }

    public function getSearchForm()
    {
        return SearchEditoType::class;
    }

    public function getType()
    {
        return EditoType::class;
    }
}
