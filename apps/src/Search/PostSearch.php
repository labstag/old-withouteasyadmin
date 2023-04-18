<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Entity\Category;
use Labstag\Entity\User;
use Labstag\Lib\SearchLib;

class PostSearch extends SearchLib
{

    public ?Category $category = null;

    public ?string $etape = null;

    public ?DateTime $published = null;

    public ?string $title = null;

    public ?User $user = null;
}
