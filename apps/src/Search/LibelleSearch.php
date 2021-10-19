<?php

namespace Labstag\Search;

class LibelleSearch
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
