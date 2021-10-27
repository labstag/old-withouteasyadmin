<?php

namespace Labstag\Search;

use Labstag\Lib\LibSearch;

class CategorySearch extends LibSearch
{
    public $name;

    public function search(array $get, $doctrine)
    {
        unset($doctrine);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
