<?php

namespace Labstag\Domain;

use Labstag\Entity\Template;

use Labstag\Form\Admin\Search\TemplateType as SearchTemplateType;
use Labstag\Form\Admin\TemplateType;
use Labstag\Lib\DomainLib;
use Labstag\Repository\TemplateRepository;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\Search\TemplateSearch;
use Symfony\Contracts\Translation\TranslatorInterface;

class TemplateDomain extends DomainLib
{
    public function __construct(
        protected TemplateRequestHandler $templateRequestHandler,
        protected TemplateRepository $templateRepository,
        TranslatorInterface $translator
    )
    {
        parent::__construct($translator);
    }

    public function getEntity()
    {
        return Template::class;
    }

    public function getRepository()
    {
        return $this->templateRepository;
    }

    public function getRequestHandler()
    {
        return $this->templateRequestHandler;
    }

    public function getSearchData()
    {
        return TemplateSearch::class;
    }

    public function getSearchForm()
    {
        return SearchTemplateType::class;
    }

    /**
     * @return mixed[]
     */
    public function getTitles(): array
    {
        return [
            'admin_template_index'   => $this->translator->trans('template.title', [], 'admin.breadcrumb'),
            'admin_template_edit'    => $this->translator->trans('template.edit', [], 'admin.breadcrumb'),
            'admin_template_new'     => $this->translator->trans('template.new', [], 'admin.breadcrumb'),
            'admin_template_trash'   => $this->translator->trans('template.trash', [], 'admin.breadcrumb'),
            'admin_template_preview' => $this->translator->trans('template.preview', [], 'admin.breadcrumb'),
            'admin_template_show'    => $this->translator->trans('template.show', [], 'admin.breadcrumb'),
        ];
    }

    public function getType()
    {
        return TemplateType::class;
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_template_edit',
            'empty'   => 'api_action_empty',
            'list'    => 'admin_template_index',
            'new'     => 'admin_template_new',
            'preview' => 'admin_template_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_template_show',
            'trash'   => 'admin_template_trash',
        ];
    }
}
