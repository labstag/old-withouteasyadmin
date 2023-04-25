<?php

namespace Labstag\Domain;

use Labstag\Entity\Profil;
use Labstag\Form\Admin\ProfilType;
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
        return ['edit' => 'admin/profil.html.twig'];
    }

    public function getTitles(): array
    {
        return [
            'admin_profil' => $this->translator->trans('profil.title', [], 'admin.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return ProfilType::class;
    }
}
