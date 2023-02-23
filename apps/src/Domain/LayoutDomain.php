<?php

namespace Labstag\Domain;

use Labstag\Entity\Layout;
use Labstag\Form\Admin\LayoutType;

use Labstag\Form\Admin\Search\LayoutType as SearchLayoutType;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\ServiceEntityRepositoryLib;
use Labstag\Repository\LayoutRepository;
use Labstag\RequestHandler\LayoutRequestHandler;
use Labstag\Search\LayoutSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class LayoutDomain extends DomainLib
{
    public function __construct(
        protected LayoutRequestHandler $layoutRequestHandler,
        protected LayoutRepository $layoutRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity(): string
    {
        return Layout::class;
    }

    public function getRepository(): ServiceEntityRepositoryLib
    {
        return $this->layoutRepository;
    }

    public function getRequestHandler(): RequestHandlerLib
    {
        return $this->layoutRequestHandler;
    }

    public function getSearchData(): LayoutSearch
    {
        return new LayoutSearch();
    }

    public function getSearchForm(): string
    {
        return SearchLayoutType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_layout_index'   => $this->translator->trans('layout.title', [], 'admin.breadcrumb'),
            'admin_layout_edit'    => $this->translator->trans('layout.edit', [], 'admin.breadcrumb'),
            'admin_layout_new'     => $this->translator->trans('layout.new', [], 'admin.breadcrumb'),
            'admin_layout_trash'   => $this->translator->trans('layout.trash', [], 'admin.breadcrumb'),
            'admin_layout_preview' => $this->translator->trans('layout.preview', [], 'admin.breadcrumb'),
            'admin_layout_show'    => $this->translator->trans('layout.show', [], 'admin.breadcrumb'),
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
            'edit'     => 'admin_layout_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_layout_index',
            'preview'  => 'admin_layout_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_layout_show',
            'trash'    => 'admin_layout_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
