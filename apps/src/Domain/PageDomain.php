<?php

namespace Labstag\Domain;

use Labstag\Entity\Page;

use Labstag\Form\Gestion\PageType;
use Labstag\Form\Gestion\Search\PageType as SearchPageType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\PageSearch;

class PageDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Page::class;
    }

    public function getSearchData(): PageSearch
    {
        return new PageSearch();
    }

    public function getSearchForm(): string
    {
        return SearchPageType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/page/index.html.twig',
            'trash'   => 'gestion/page/index.html.twig',
            'show'    => 'gestion/page/show.html.twig',
            'preview' => 'gestion/page/show.html.twig',
            'edit'    => 'gestion/page/form.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_page_index'   => $this->translator->trans('page.title', [], 'gestion.breadcrumb'),
            'gestion_page_edit'    => $this->translator->trans('page.edit', [], 'gestion.breadcrumb'),
            'gestion_page_new'     => $this->translator->trans('page.new', [], 'gestion.breadcrumb'),
            'gestion_page_trash'   => $this->translator->trans('page.trash', [], 'gestion.breadcrumb'),
            'gestion_page_preview' => $this->translator->trans('page.preview', [], 'gestion.breadcrumb'),
            'gestion_page_show'    => $this->translator->trans('page.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return PageType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_page_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_page_index',
            'new'      => 'gestion_page_new',
            'preview'  => 'gestion_page_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_page_show',
            'trash'    => 'gestion_page_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
