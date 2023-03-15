<?php

namespace Labstag\Domain;

use Labstag\Entity\Libelle;
use Labstag\Form\Admin\LibelleType;

use Labstag\Form\Admin\Search\LibelleType as SearchLibelleType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\LibelleRepository;
use Labstag\Search\LibelleSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class LibelleDomain extends DomainLib implements DomainInterface
{
    public function __construct(
        protected LibelleRepository $libelleRepository,
        protected LibelleSearch $libelleSearch,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Libelle::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->libelleRepository;
    }

    public function getSearchData(): LibelleSearch
    {
        return $this->libelleSearch;
    }

    public function getSearchForm(): string
    {
        return SearchLibelleType::class;
    }

    public function getTitles(): array
    {
        return [
            'admin_libelle_index'   => $this->translator->trans('libelle.title', [], 'admin.breadcrumb'),
            'admin_libelle_edit'    => $this->translator->trans('libelle.edit', [], 'admin.breadcrumb'),
            'admin_libelle_new'     => $this->translator->trans('libelle.new', [], 'admin.breadcrumb'),
            'admin_libelle_trash'   => $this->translator->trans('libelle.trash', [], 'admin.breadcrumb'),
            'admin_libelle_preview' => $this->translator->trans('libelle.preview', [], 'admin.breadcrumb'),
            'admin_libelle_show'    => $this->translator->trans('libelle.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return LibelleType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_libelle_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_libelle_index',
            'new'      => 'admin_libelle_new',
            'preview'  => 'admin_libelle_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_libelle_show',
            'trash'    => 'admin_libelle_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
