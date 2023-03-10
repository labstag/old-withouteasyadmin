<?php

namespace Labstag\Search\User;

use Labstag\Lib\SearchLib;
use Symfony\Component\Security\Core\User\UserInterface;

class EmailUserSearch extends SearchLib
{
    public ?string $etape = null;

    public ?UserInterface $user = null;
}
