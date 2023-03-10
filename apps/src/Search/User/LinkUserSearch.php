<?php

namespace Labstag\Search\User;

use Labstag\Lib\SearchLib;
use Symfony\Component\Security\Core\User\UserInterface;

class LinkUserSearch extends SearchLib
{
    public ?UserInterface $user = null;
}
