<?php

namespace Labstag\Domain;

use Labstag\Form\Admin\ProfilType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\ProfilSearch;

class AdminDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return 'admin';
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
            'admin_trash' => $this->translator->trans('trash.title', [], 'admin.header'),
        ];
    }

    public function getType(): string
    {
        return ProfilType::class;
    }
}
