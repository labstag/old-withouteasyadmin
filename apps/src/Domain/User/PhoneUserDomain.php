<?php

namespace Labstag\Domain\User;

use Labstag\Entity\PhoneUser;
use Labstag\Form\Admin\Search\User\PhoneUserType as SearchPhoneUserType;
use Labstag\Form\Admin\User\PhoneUserType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\PhoneUserRequestHandler;
use Labstag\Search\User\PhoneUserSearch;

class PhoneUserDomain extends DomainLib
{
    public function __construct(
        protected PhoneUserRequestHandler $phoneUserRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return PhoneUser::class;
    }

    public function getRequestHandler()
    {
        return $this->phoneUserRequestHandler;
    }

    public function getSearchData()
    {
        return PhoneUserSearch::class;
    }

    public function getSearchForm()
    {
        return SearchPhoneUserType::class;
    }

    public function getType()
    {
        return PhoneUserType::class;
    }
}
