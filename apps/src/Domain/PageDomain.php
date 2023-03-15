<?php

namespace Labstag\Domain;

use Labstag\Entity\Page;

use Labstag\Form\Admin\PageType;
use Labstag\Form\Admin\Search\PageType as SearchPageType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\PageRepository;
use Labstag\Search\PageSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class PageDomain extends DomainLib implements DomainInterface
{
    public function __construct(
        protected PageRepository $pageRepository,
        protected PageSearch $pageSearch,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Page::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->pageRepository;
    }

    public function getSearchData(): PageSearch
    {
        return $this->pageSearch;
    }

    public function getSearchForm(): string
    {
        return SearchPageType::class;
    }

    public function getTitles(): array
    {
        return [
            'admin_page_index'   => $this->translator->trans('page.title', [], 'admin.breadcrumb'),
            'admin_page_edit'    => $this->translator->trans('page.edit', [], 'admin.breadcrumb'),
            'admin_page_new'     => $this->translator->trans('page.new', [], 'admin.breadcrumb'),
            'admin_page_trash'   => $this->translator->trans('page.trash', [], 'admin.breadcrumb'),
            'admin_page_preview' => $this->translator->trans('page.preview', [], 'admin.breadcrumb'),
            'admin_page_show'    => $this->translator->trans('page.show', [], 'admin.breadcrumb'),
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
            'edit'     => 'admin_page_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_page_index',
            'new'      => 'admin_page_new',
            'preview'  => 'admin_page_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_page_show',
            'trash'    => 'admin_page_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
