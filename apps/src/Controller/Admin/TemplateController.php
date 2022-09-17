<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Template;
use Labstag\Form\Admin\Search\TemplateType as SearchTemplateType;
use Labstag\Form\Admin\TemplateType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\Search\TemplateSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/template')]
class TemplateController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_template_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_template_new', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $attachFormService,
        ?Template $template,
        TemplateRequestHandler $templateRequestHandler
    ): Response
    {
        return $this->form(
            $attachFormService,
            $templateRequestHandler,
            TemplateType::class,
            is_null($template) ? new Template() : $template
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_template_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_template_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            Template::class,
            'admin/template/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_template_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_template_preview', methods: ['GET'])]
    public function showOrPreview(Template $template): Response
    {
        return $this->renderShowOrPreview(
            $template,
            'admin/template/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
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

    /**
     * @return array<string, class-string<\Labstag\Form\Admin\Search\TemplateType>>|array<string, \TemplateSearch>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchTemplateType::class,
            'data' => new TemplateSearch(),
        ];
    }

    /**
     * @return mixed[]
     */
    protected function setBreadcrumbsData(): array
    {
        return array_merge(
            parent::setBreadcrumbsData(),
            [
                [
                    'title' => $this->translator->trans('template.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_template_index',
                ],
                [
                    'title' => $this->translator->trans('template.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_template_edit',
                ],
                [
                    'title' => $this->translator->trans('template.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_template_new',
                ],
                [
                    'title' => $this->translator->trans('template.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_template_trash',
                ],
                [
                    'title' => $this->translator->trans('template.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_template_preview',
                ],
                [
                    'title' => $this->translator->trans('template.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_template_show',
                ],
            ]
        );
    }

    /**
     * @return mixed[]
     */
    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return [...$headers, ...[
            'admin_template' => $this->translator->trans('template.title', [], 'admin.header'),
        ]];
    }
}
