<?php

namespace Labstag\Service;

use DateTime;

class BookmarkService
{
    public function process(
        string $user,
        string $url,
        string $name,
        string $icon,
        DateTime $date
    )
    {
        dump($user, $url, $name, $icon, $date);
    }
}
