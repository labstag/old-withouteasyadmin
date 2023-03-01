<?php

namespace Labstag\Search;

use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class BookmarkSearch extends SearchLib
{

    public $category;

    public $etape;

    public $name;

    public ?User $user = null;
}
