<?php

namespace Labstag\Search\User;

use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class EmailUserSearch extends SearchLib
{
    public ?string $etape = null;

    public ?User $user = null;
}
