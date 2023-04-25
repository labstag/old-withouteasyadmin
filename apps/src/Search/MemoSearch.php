<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class MemoSearch extends SearchLib
{
    public ?DateTime $dateEnd = null;

    public ?DateTime $dateStart = null;

    public ?string $etape = null;

    public ?string $title = null;

    public ?User $user = null;
}
