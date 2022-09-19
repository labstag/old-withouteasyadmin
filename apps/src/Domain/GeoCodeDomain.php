<?php

namespace Labstag\Domain;

use Labstag\Entity\GeoCode;
use Labstag\Form\Admin\GeoCodeType;
use Labstag\Form\Admin\Search\GeoCodeType as SearchGeoCodeType;
use Labstag\Lib\DomainLib;
use Labstag\RequestHandler\GeoCodeRequestHandler;
use Labstag\Search\GeoCodeSearch;

class GeoCodeDomain extends DomainLib
{
    public function __construct(
        protected GeoCodeRequestHandler $geoCodeRequestHandler
    )
    {
    }

    public function getEntity()
    {
        return GeoCode::class;
    }

    public function getRequestHandler()
    {
        return $this->geoCodeRequestHandler;
    }

    public function getSearchData()
    {
        return GeoCodeSearch::class;
    }

    public function getSearchForm()
    {
        return SearchGeoCodeType::class;
    }

    public function getType()
    {
        return GeoCodeType::class;
    }
}
