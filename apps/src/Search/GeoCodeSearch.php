<?php

namespace Labstag\Search;

use Labstag\Lib\SearchLib;

class GeoCodeSearch extends SearchLib
{

    public ?string $communityname = null;

    public ?string $countrycode = null;

    public ?string $placename = null;

    public ?string $postalcode = null;

    public ?string $provincename = null;

    public ?string $statename = null;
}
