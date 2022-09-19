<?php

namespace Labstag\Domain;

use Labstag\Entity\Libelle;
use Labstag\Form\Admin\LibelleType;
use Labstag\Form\Admin\Search\LibelleType as SearchLibelleType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\LibelleRequestHandler;
use Labstag\Search\LibelleSearch;

class LibelleDomain extends DomainLib
{
    public function __construct(
        protected LibelleRequestHandler $libelleRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Libelle::class;
    }

    public function getRequestHandler()
    {
        return $this->libelleRequestHandler;
    }

    public function getSearchData()
    {
        return LibelleSearch::class;
    }

    public function getSearchForm()
    {
        return SearchLibelleType::class;
    }

    public function getType()
    {
        return LibelleType::class;
    }
}
