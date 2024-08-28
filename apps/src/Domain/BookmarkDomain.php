<?php

namespace Labstag\Domain;

use Labstag\Entity\Bookmark;

use Labstag\Form\Gestion\Bookmark\PrincipalType;
use Labstag\Form\Gestion\Search\BookmarkType as SearchBookmarkType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\BookmarkSearch;

class BookmarkDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Bookmark::class;
    }

    public function getSearchData(): BookmarkSearch
    {
        return new BookmarkSearch();
    }

    public function getSearchForm(): string
    {
        return SearchBookmarkType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/bookmark/index.html.twig',
            'trash'   => 'gestion/bookmark/index.html.twig',
            'edit'    => 'gestion/bookmark/form.html.twig',
            'new'     => 'gestion/bookmark/form.html.twig',
            'import'  => 'gestion/bookmark/import.html.twig',
            'show'    => 'gestion/bookmark/show.html.twig',
            'preview' => 'gestion/bookmark/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_bookmark_index'   => $this->translator->trans('bookmark.title', [], 'gestion.breadcrumb'),
            'gestion_bookmark_edit'    => $this->translator->trans('bookmark.edit', [], 'gestion.breadcrumb'),
            'gestion_bookmark_import'  => $this->translator->trans('bookmark.import', [], 'gestion.breadcrumb'),
            'gestion_bookmark_new'     => $this->translator->trans('bookmark.new', [], 'gestion.breadcrumb'),
            'gestion_bookmark_trash'   => $this->translator->trans('bookmark.trash', [], 'gestion.breadcrumb'),
            'gestion_bookmark_preview' => $this->translator->trans('bookmark.preview', [], 'gestion.breadcrumb'),
            'gestion_bookmark_show'    => $this->translator->trans('bookmark.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return PrincipalType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'gestion_bookmark_edit',
            'empty'    => 'api_action_empty',
            'import'   => 'gestion_bookmark_import',
            'list'     => 'gestion_bookmark_index',
            'new'      => 'gestion_bookmark_new',
            'preview'  => 'gestion_bookmark_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_bookmark_show',
            'trash'    => 'gestion_bookmark_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
