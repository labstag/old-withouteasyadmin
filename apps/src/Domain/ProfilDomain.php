<?php

namespace Labstag\Domain;

use Labstag\Entity\Profil;
use Labstag\Form\Gestion\ProfilType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\ProfilSearch;

class ProfilDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Profil::class;
    }

    public function getSearchData(): ProfilSearch
    {
        return new ProfilSearch();
    }

    public function getTemplates(): array
    {
        return ['edit' => 'gestion/profil.html.twig'];
    }

    public function getTitles(): array
    {
        return [
            'gestion_profil' => $this->translator->trans('profil.title', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return ProfilType::class;
    }
}
