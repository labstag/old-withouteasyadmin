<?php

namespace Labstag\Domain\User;

use Labstag\Entity\EmailUser;
use Labstag\Form\Admin\Search\User\EmailUserType as SearchEmailUserType;
use Labstag\Form\Admin\User\EmailUserType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\EmailUserRequestHandler;
use Labstag\Search\User\EmailUserSearch;

class EmailUserDomain extends DomainLib
{
    public function __construct(
        protected EmailUserRequestHandler $emailUserRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return EmailUser::class;
    }

    public function getRequestHandler()
    {
        return $this->emailUserRequestHandler;
    }

    public function getSearchData()
    {
        return EmailUserSearch::class;
    }

    public function getSearchForm()
    {
        return SearchEmailUserType::class;
    }

    public function getType()
    {
        return EmailUserType::class;
    }
}
