<?php

namespace Labstag\Search;

use Labstag\Lib\LibSearch;

class LibelleSearch extends LibSearch
{
    public $nom;

    public function search(array $get, $doctrine)
    {
        unset($doctrine);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
