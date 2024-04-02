<?php

namespace Labstag\Domain;

use Labstag\Entity\GeoCode;
use Labstag\Form\Gestion\GeoCodeType;

use Labstag\Form\Gestion\Search\GeoCodeType as SearchGeoCodeType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\GeoCodeSearch;

class GeoCodeDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return GeoCode::class;
    }

    public function getSearchData(): GeoCodeSearch
    {
        return new GeoCodeSearch();
    }

    public function getSearchForm(): string
    {
        return SearchGeoCodeType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/geocode/index.html.twig',
            'trash'   => 'gestion/geocode/index.html.twig',
            'show'    => 'gestion/geocode/show.html.twig',
            'preview' => 'gestion/geocode/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_geocode_index'   => $this->translator->trans('geocode.title', [], 'gestion.breadcrumb'),
            'gestion_geocode_edit'    => $this->translator->trans('geocode.edit', [], 'gestion.breadcrumb'),
            'gestion_geocode_new'     => $this->translator->trans('geocode.new', [], 'gestion.breadcrumb'),
            'gestion_geocode_trash'   => $this->translator->trans('geocode.trash', [], 'gestion.breadcrumb'),
            'gestion_geocode_preview' => $this->translator->trans('geocode.preview', [], 'gestion.breadcrumb'),
            'gestion_geocode_show'    => $this->translator->trans('geocode.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return GeoCodeType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'      => 'api_action_delete',
            'destroy'     => 'api_action_destroy',
            'edit'        => 'gestion_geocode_edit',
            'empty'       => 'api_action_empty',
            'list'        => 'gestion_geocode_index',
            'new'         => 'gestion_geocode_new',
            'restore'     => 'api_action_restore',
            'show'        => 'gestion_geocode_show',
            'trash'       => 'gestion_geocode_trash',
            'trashdelete' => 'gestion_geocode_destroy',
        ];
    }
}
