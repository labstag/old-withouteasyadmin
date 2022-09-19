<?php

namespace Labstag\Domain\User;

use Labstag\Entity\Groupe;
use Labstag\Form\Admin\Search\GroupeType as SearchGroupeType;
use Labstag\Form\Admin\User\GroupeType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\GroupeRequestHandler;
use Labstag\Search\GroupeSearch;

class GroupeDomain extends DomainLib
{
    public function __construct(
        protected GroupeRequestHandler $groupeRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return Groupe::class;
    }

    public function getRequestHandler()
    {
        return $this->groupeRequestHandler;
    }

    public function getSearchData()
    {
        return GroupeSearch::class;
    }

    public function getSearchForm()
    {
        return SearchGroupeType::class;
    }

    public function getType()
    {
        return GroupeType::class;
    }
}
