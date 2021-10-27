<?php

namespace Labstag\Search;

use Labstag\Lib\LibSearch;

class TemplateSearch extends LibSearch
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
