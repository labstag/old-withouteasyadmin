<?php

namespace Labstag\Domain;

use Labstag\Entity\Template;

use Labstag\Form\Gestion\Search\TemplateType as SearchTemplateType;
use Labstag\Form\Gestion\TemplateType;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\DomainLib;
use Labstag\Search\TemplateSearch;

class TemplateDomain extends DomainLib implements DomainInterface
{
    public function getEntity(): string
    {
        return Template::class;
    }

    public function getSearchData(): TemplateSearch
    {
        return new TemplateSearch();
    }

    public function getSearchForm(): string
    {
        return SearchTemplateType::class;
    }

    public function getTemplates(): array
    {
        return [
            'index'   => 'gestion/template/index.html.twig',
            'trash'   => 'gestion/template/index.html.twig',
            'show'    => 'gestion/template/show.html.twig',
            'preview' => 'gestion/template/show.html.twig',
        ];
    }

    public function getTitles(): array
    {
        return [
            'gestion_template_index'   => $this->translator->trans('template.title', [], 'gestion.breadcrumb'),
            'gestion_template_edit'    => $this->translator->trans('template.edit', [], 'gestion.breadcrumb'),
            'gestion_template_new'     => $this->translator->trans('template.new', [], 'gestion.breadcrumb'),
            'gestion_template_trash'   => $this->translator->trans('template.trash', [], 'gestion.breadcrumb'),
            'gestion_template_preview' => $this->translator->trans('template.preview', [], 'gestion.breadcrumb'),
            'gestion_template_show'    => $this->translator->trans('template.show', [], 'gestion.breadcrumb'),
        ];
    }

    public function getType(): string
    {
        return TemplateType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'gestion_template_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'gestion_template_index',
            'new'     => 'gestion_template_new',
            'preview' => 'gestion_template_preview',
            'restore' => 'api_action_restore',
            'show'    => 'gestion_template_show',
            'trash'   => 'gestion_template_trash',
        ];
    }
}
