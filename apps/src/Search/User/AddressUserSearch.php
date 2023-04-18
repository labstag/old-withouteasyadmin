<?php

namespace Labstag\Search\User;

use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class AddressUserSearch extends SearchLib
{

    public ?string $city = null;

    public ?string $country = null;

    public ?User $user = null;
}
