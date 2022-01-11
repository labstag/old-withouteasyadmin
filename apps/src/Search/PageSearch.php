<?php

namespace Labstag\Search;

use Labstag\Lib\LibSearch;

class PageSearch extends LibSearch
{

    public $etape;

    public $name;

    public function search(array $get, $doctrine)
    {
        unset($doctrine);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
