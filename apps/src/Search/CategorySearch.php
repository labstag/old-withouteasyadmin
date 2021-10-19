<?php

namespace Labstag\Search;

class CategorySearch
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
