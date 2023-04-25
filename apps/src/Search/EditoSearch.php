<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class EditoSearch extends SearchLib
{
    public ?string $etape = null;

    public ?DateTime $published = null;

    public ?string $title = null;

    public ?User $user = null;
}
