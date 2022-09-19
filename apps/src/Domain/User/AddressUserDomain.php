<?php

namespace Labstag\Domain\User;

use Labstag\Entity\AddressUser;
use Labstag\Form\Admin\Search\User\AddressUserType as SearchAddressUserType;
use Labstag\Form\Admin\User\AddressUserType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\AddressUserRequestHandler;
use Labstag\Search\User\AddressUserSearch;

class AddressUserDomain extends DomainLib
{
    public function __construct(
        protected AddressUserRequestHandler $addressUserRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return AddressUser::class;
    }

    public function getRequestHandler()
    {
        return $this->addressUserRequestHandler;
    }

    public function getSearchData()
    {
        return AddressUserSearch::class;
    }

    public function getSearchForm()
    {
        return SearchAddressUserType::class;
    }

    public function getType()
    {
        return AddressUserType::class;
    }
}
