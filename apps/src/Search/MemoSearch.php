<?php

namespace Labstag\Search;

use Labstag\Lib\SearchLib;
use Symfony\Component\Security\Core\User\UserInterface;

class MemoSearch extends SearchLib
{
    public ?string $dateEnd = null;

    public ?string $dateStart = null;

    public ?string $etape = null;

    public ?string $title = null;

    public ?UserInterface $user = null;
}
