<?php

namespace Labstag\Domain\User;

use Labstag\Entity\LinkUser;
use Labstag\Form\Admin\Search\User\LinkUserType as SearchLinkUserType;
use Labstag\Form\Admin\User\LinkUserType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\LinkUserRequestHandler;
use Labstag\Search\User\LinkUserSearch;

class LinkUserDomain extends DomainLib
{
    public function __construct(
        protected LinkUserRequestHandler $linkUserRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return LinkUser::class;
    }

    public function getRequestHandler()
    {
        return $this->linkUserRequestHandler;
    }

    public function getSearchData()
    {
        return LinkUserSearch::class;
    }

    public function getSearchForm()
    {
        return SearchLinkUserType::class;
    }

    public function getType()
    {
        return LinkUserType::class;
    }
}
