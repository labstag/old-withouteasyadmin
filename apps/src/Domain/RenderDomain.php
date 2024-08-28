<?php

namespace Labstag\Domain;

use Labstag\Entity\Render;

use Labstag\Form\Gestion\RenderType;
use Labstag\Form\Gestion\Search\RenderType as SearchRenderType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\RenderSearch;

class RenderDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Render::class;
    }

    public function getSearchData(): RenderSearch
    {
        return new RenderSearch();
    }

    public function getSearchForm(): string
    {
        return SearchRenderType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/render/index.html.twig',
            'trash'   => 'gestion/render/index.html.twig',
            'show'    => 'gestion/render/show.html.twig',
            'preview' => 'gestion/render/show.html.twig',
            'edit'    => 'gestion/render/form.html.twig',
            'new'     => 'gestion/render/form.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_render_index'   => $this->translator->trans('render.title', [], 'gestion.breadcrumb'),
            'gestion_render_edit'    => $this->translator->trans('render.edit', [], 'gestion.breadcrumb'),
            'gestion_render_new'     => $this->translator->trans('render.new', [], 'gestion.breadcrumb'),
            'gestion_render_trash'   => $this->translator->trans('render.trash', [], 'gestion.breadcrumb'),
            'gestion_render_preview' => $this->translator->trans('render.preview', [], 'gestion.breadcrumb'),
            'gestion_render_show'    => $this->translator->trans('render.show', [], 'gestion.breadcrumb'),
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
            'edit'     => 'gestion_render_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'gestion_render_index',
            'new'      => 'gestion_render_new',
            'preview'  => 'gestion_render_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'gestion_render_show',
            'trash'    => 'gestion_render_trash',
            'workflow' => 'api_action_workflow',
        ];
    }
}
