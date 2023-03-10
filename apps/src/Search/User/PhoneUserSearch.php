<?php

namespace Labstag\Search\User;

use Labstag\Lib\SearchLib;
use Symfony\Component\Security\Core\User\UserInterface;

class PhoneUserSearch extends SearchLib
{
    public ?string $country = null;

    public ?string $etape = null;

    public ?UserInterface $user = null;
}
