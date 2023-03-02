<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Entity\Category;
use Labstag\Lib\SearchLib;
use Symfony\Component\Security\Core\User\UserInterface;

class PostSearch extends SearchLib
{

    public ?Category $category = null;

    public ?string $etape = null;

    public ?DateTime $published = null;

    public ?string $title = null;

    public ?UserInterface $user = null;
}
