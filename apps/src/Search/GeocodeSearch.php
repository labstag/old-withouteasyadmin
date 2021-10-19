<?php

namespace Labstag\Search;

class GeocodeSearch
{

    public $communityname;

    public $countrycode;

    public $placename;

    public $postalcode;

    public $provincename;

    public $statename;

    public function search(array $get, $doctrine)
    {
        unset($doctrine);
        foreach ($get as $key => $value) {
            $this->{$key} = $value;
        }
    }
}
