<?php

namespace Labstag\Domain;

use Labstag\Entity\Configuration;
use Labstag\Form\Admin\ProfilType;
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
        return ['edit' => 'admin/param.html.twig'];
    }

    public function getTitles(): array
    {
        return [
            'admin_param' => $this->translator->trans('param.title', [], 'admin.header'),
        ];
    }

    public function getType(): string
    {
        return ProfilType::class;
    }
}
