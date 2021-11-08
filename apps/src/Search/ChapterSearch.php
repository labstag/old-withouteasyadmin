<?php

namespace Labstag\Search;

use DateTime;
use Labstag\Lib\LibSearch;

class ChapterSearch extends LibSearch
{
    public $etape;

    public $published;

    public $title;

    public function search(array $get, $doctrine)
    {
        unset($doctrine);
        $date = new DateTime();
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
            if ('published' == $key) {
                if (!empty($value)) {
                    [
                        $year,
                        $month,
                        $day,
                    ] = explode('-', $value);
                    $date->setDate($year, $month, $day);
                    $this->{$key} = $date;

                    continue;
                }

                $this->{$key} = null;

                continue;
            }
        }
    }
}
