<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Lib\SearchLib;
use Symfony\Component\Security\Core\User\UserInterface;

class HistorySearch extends SearchLib
{

    public ?string $etape = null;

    public ?string $name = null;

    public ?DateTime $published = null;

    public ?UserInterface $user = null;
}
