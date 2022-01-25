<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Entity\Category;
use Labstag\Entity\User;
use Labstag\Lib\LibSearch;

class PostSearch extends LibSearch
{

    public $etape;

    public $published;

    public $refcategory;

    public $refuser;

    public $title;
}
