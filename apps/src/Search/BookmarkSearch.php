<?php

namespace Labstag\Search;

use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class BookmarkSearch extends SearchLib
{

    public $etape;

    public $name;

    public $refcategory;

    public ?User $refuser;
}
