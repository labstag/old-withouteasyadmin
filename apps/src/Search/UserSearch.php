<?php

namespace Labstag\Search;

use Labstag\Entity\Groupe;
use Labstag\Lib\SearchLib;

class UserSearch extends SearchLib
{

    public ?string $email = null;

    public ?string $etape = null;

    public ?Groupe $groupe = null;

    public ?string $username = null;
}
