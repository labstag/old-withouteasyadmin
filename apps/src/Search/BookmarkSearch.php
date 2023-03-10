<?php

namespace Labstag\Search;

use Labstag\Entity\Category;
use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class BookmarkSearch extends SearchLib
{

    public ?Category $category = null;

    public ?string $etape = null;

    public ?string $name = null;

    public ?User $user = null;
}
