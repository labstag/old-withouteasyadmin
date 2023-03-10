<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Lib\SearchLib;
use Symfony\Component\Security\Core\User\UserInterface;

class EditoSearch extends SearchLib
{
    public ?string $etape = null;

    public ?DateTime $published = null;

    public ?string $title = null;

    public ?UserInterface $user = null;
}
