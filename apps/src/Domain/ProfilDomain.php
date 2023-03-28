<?php

namespace Labstag\Domain;

use Labstag\Entity\Profil;
use Labstag\Form\Admin\ProfilType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RepositoryLib;
use Labstag\Search\ProfilSearch;

class ProfilDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Profil::class;
    }

    public function getRepository(): RepositoryLib
    {
        return $this->userRepository;
    }

    public function getSearchData(): ProfilSearch
    {
        return $this->profilSearch;
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
