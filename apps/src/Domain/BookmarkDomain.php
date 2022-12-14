<?php

namespace Labstag\Domain;

use Labstag\Entity\Bookmark;

use Labstag\Form\Admin\Bookmark\PrincipalType;
use Labstag\Form\Admin\Search\BookmarkType as SearchBookmarkType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\BookmarkRepository;
use Labstag\RequestHandler\BookmarkRequestHandler;
use Labstag\Search\BookmarkSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class BookmarkDomain extends DomainLib
{
    public function __construct(
        protected BookmarkRequestHandler $bookmarkRequestHandler,
        protected BookmarkRepository $bookmarkRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return Bookmark::class;
    }

    public function getRepository()
    {
        return $this->bookmarkRepository;
    }

    public function getRequestHandler()
    {
        return $this->bookmarkRequestHandler;
    }

    public function getSearchData()
    {
        return new BookmarkSearch();
    }

    public function getSearchForm()
    {
        return SearchBookmarkType::class;
    }

    /**
     * @return mixed[]
     */
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

    public function getType()
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
