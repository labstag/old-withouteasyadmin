<?php

namespace Labstag\Search\User;

use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class LinkUserSearch extends SearchLib
{
    public ?User $user = null;
}
