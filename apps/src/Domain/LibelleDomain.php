<?php

namespace Labstag\Domain;

use Labstag\Entity\Libelle;
use Labstag\Form\Gestion\LibelleType;

use Labstag\Form\Gestion\Search\LibelleType as SearchLibelleType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\LibelleSearch;

class LibelleDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Libelle::class;
    }

    public function getSearchData(): LibelleSearch
    {
        return new LibelleSearch();
    }

    public function getSearchForm(): string
    {
        return SearchLibelleType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/libelle/index.html.twig',
            'trash'   => 'gestion/libelle/index.html.twig',
            'show'    => 'gestion/libelle/show.html.twig',
            'preview' => 'gestion/libelle/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_libelle_index'   => $this->translator->trans('libelle.title', [], 'gestion.breadcrumb'),
            'gestion_libelle_edit'    => $this->translator->trans('libelle.edit', [], 'gestion.breadcrumb'),
            'gestion_libelle_new'     => $this->translator->trans('libelle.new', [], 'gestion.breadcrumb'),
            'gestion_libelle_trash'   => $this->translator->trans('libelle.trash', [], 'gestion.breadcrumb'),
            'gestion_libelle_preview' => $this->translator->trans('libelle.preview', [], 'gestion.breadcrumb'),
            'gestion_libelle_show'    => $this->translator->trans('libelle.show', [], 'gestion.breadcrumb'),
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
            'edit'     => 'gestion_libelle_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_libelle_index',
            'new'      => 'gestion_libelle_new',
            'preview'  => 'gestion_libelle_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_libelle_show',
            'trash'    => 'gestion_libelle_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
