<?php

namespace Labstag\Domain;

use Labstag\Entity\Configuration;
use Labstag\Form\Gestion\ProfilType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\ProfilSearch;

class ConfigurationDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Configuration::class;
    }

    public function getSearchData(): ProfilSearch
    {
        return new ProfilSearch();
    }

    public function getTemplates(): array
    {
        return ['edit' => 'gestion/param.html.twig'];
    }

    public function getTitles(): array
    {
        return [
            'gestion_param' => $this->translator->trans('param.title', [], 'gestion.header'),
        ];
    }

    public function getType(): string
    {
        return ProfilType::class;
    }
}
