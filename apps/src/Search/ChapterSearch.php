<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Lib\SearchLib;

class ChapterSearch extends SearchLib
{

    public ?string $etape = null;

    public ?DateTime $published = null;

    public ?string $title = null;
}
