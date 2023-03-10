<?php

namespace Labstag\Search\User;

use Labstag\Lib\SearchLib;
use Symfony\Component\Security\Core\User\UserInterface;

class AddressUserSearch extends SearchLib
{
    public ?string $city = null;

    public ?string $country = null;

    public ?UserInterface $user = null;
}
