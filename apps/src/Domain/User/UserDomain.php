<?php

namespace Labstag\Domain\User;

use Labstag\Entity\User;
use Labstag\Form\Admin\Search\UserType as SearchUserType;
use Labstag\Form\Admin\User\UserType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Search\UserSearch;

class UserDomain extends DomainLib
{
    public function __construct(
        protected UserRequestHandler $userRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return User::class;
    }

    public function getRequestHandler()
    {
        return $this->userRequestHandler;
    }

    public function getSearchData()
    {
        return UserSearch::class;
    }

    public function getSearchForm()
    {
        return SearchUserType::class;
    }

    public function getType()
    {
        return UserType::class;
    }
}
