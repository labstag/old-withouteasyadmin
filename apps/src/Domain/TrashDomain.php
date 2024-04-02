<?php

namespace Labstag\Domain;

use Labstag\Form\Gestion\ProfilType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\ProfilSearch;

class TrashDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return 'trash';
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
            'gestion_trash' => $this->translator->trans('trash.title', [], 'gestion.header'),
        ];
    }

    public function getType(): string
    {
        return ProfilType::class;
    }
}
