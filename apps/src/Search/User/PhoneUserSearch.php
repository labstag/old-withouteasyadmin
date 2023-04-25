<?php

namespace Labstag\Search\User;

use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class PhoneUserSearch extends SearchLib
{
    public ?string $country = null;

    public ?string $etape = null;

    public ?User $user = null;
}
