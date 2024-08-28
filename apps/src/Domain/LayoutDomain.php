<?php

namespace Labstag\Domain;

use Labstag\Entity\Layout;
use Labstag\Form\Gestion\LayoutType;

use Labstag\Form\Gestion\Search\LayoutType as SearchLayoutType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\LayoutSearch;

class LayoutDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Layout::class;
    }

    public function getSearchData(): LayoutSearch
    {
        return new LayoutSearch();
    }

    public function getSearchForm(): string
    {
        return SearchLayoutType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/layout/index.html.twig',
            'trash'   => 'gestion/layout/index.html.twig',
            'edit'    => 'gestion/layout/form.html.twig',
            'show'    => 'gestion/layout/show.html.twig',
            'preview' => 'gestion/layout/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_layout_index'   => $this->translator->trans('layout.title', [], 'gestion.breadcrumb'),
            'gestion_layout_edit'    => $this->translator->trans('layout.edit', [], 'gestion.breadcrumb'),
            'gestion_layout_new'     => $this->translator->trans('layout.new', [], 'gestion.breadcrumb'),
            'gestion_layout_trash'   => $this->translator->trans('layout.trash', [], 'gestion.breadcrumb'),
            'gestion_layout_preview' => $this->translator->trans('layout.preview', [], 'gestion.breadcrumb'),
            'gestion_layout_show'    => $this->translator->trans('layout.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return LayoutType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_layout_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_layout_index',
            'add'      => 'gestion_layout_new',
            'preview'  => 'gestion_layout_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_layout_show',
            'trash'    => 'gestion_layout_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
