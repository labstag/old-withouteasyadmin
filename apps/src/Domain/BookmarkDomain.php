<?php

namespace Labstag\Domain;

use Labstag\Entity\Bookmark;

use Labstag\Form\Admin\Bookmark\PrincipalType;
use Labstag\Form\Admin\Search\BookmarkType as SearchBookmarkType;
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
            'index'   => 'admin/bookmark/index.html.twig',
            'trash'   => 'admin/bookmark/index.html.twig',
            'edit'    => 'admin/bookmark/form.html.twig',
            'new'     => 'admin/bookmark/form.html.twig',
            'import'  => 'admin/bookmark/import.html.twig',
            'show'    => 'admin/bookmark/show.html.twig',
            'preview' => 'admin/bookmark/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'admin_bookmark_index'   => $this->translator->trans('bookmark.title', [], 'admin.breadcrumb'),
            'admin_bookmark_edit'    => $this->translator->trans('bookmark.edit', [], 'admin.breadcrumb'),
            'admin_bookmark_import'  => $this->translator->trans('bookmark.import', [], 'admin.breadcrumb'),
            'admin_bookmark_new'     => $this->translator->trans('bookmark.new', [], 'admin.breadcrumb'),
            'admin_bookmark_trash'   => $this->translator->trans('bookmark.trash', [], 'admin.breadcrumb'),
            'admin_bookmark_preview' => $this->translator->trans('bookmark.preview', [], 'admin.breadcrumb'),
            'admin_bookmark_show'    => $this->translator->trans('bookmark.show', [], 'admin.breadcrumb'),
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
            'edit'     => 'admin_bookmark_edit',
            'empty'    => 'api_action_empty',
            'import'   => 'admin_bookmark_import',
            'list'     => 'admin_bookmark_index',
            'new'      => 'admin_bookmark_new',
            'preview'  => 'admin_bookmark_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_bookmark_show',
            'trash'    => 'admin_bookmark_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
