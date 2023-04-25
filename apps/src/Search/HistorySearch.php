<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class HistorySearch extends SearchLib
{

    public ?string $etape = null;

    public ?string $name = null;

    public ?DateTime $published = null;

    public ?User $user = null;
}
