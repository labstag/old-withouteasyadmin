<?php

namespace Labstag\Domain;

use Labstag\Entity\Render;

use Labstag\Form\Admin\RenderType;
use Labstag\Form\Admin\Search\RenderType as SearchRenderType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Lib\RepositoryLib;
use Labstag\Search\RenderSearch;

class RenderDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Render::class;
    }

    public function getRepository(): RepositoryLib
    {
        return $this->renderRepository;
    }

    public function getSearchData(): RenderSearch
    {
        return $this->renderSearch;
    }

    public function getSearchForm(): string
    {
        return SearchRenderType::class;
    }

    public function getTitles(): array
    {
        return [
            'admin_render_index'   => $this->translator->trans('render.title', [], 'admin.breadcrumb'),
            'admin_render_edit'    => $this->translator->trans('render.edit', [], 'admin.breadcrumb'),
            'admin_render_new'     => $this->translator->trans('render.new', [], 'admin.breadcrumb'),
            'admin_render_trash'   => $this->translator->trans('render.trash', [], 'admin.breadcrumb'),
            'admin_render_preview' => $this->translator->trans('render.preview', [], 'admin.breadcrumb'),
            'admin_render_show'    => $this->translator->trans('render.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return RenderType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_render_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_render_index',
            'new'      => 'admin_render_new',
            'preview'  => 'admin_render_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_render_show',
            'trash'    => 'admin_render_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
