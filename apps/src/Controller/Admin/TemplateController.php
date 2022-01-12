<?php

namespace Labstag\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Template;
use Labstag\Form\Admin\Search\TemplateType as SearchTemplateType;
use Labstag\Form\Admin\TemplateType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\TemplateRepository;
use Labstag\RequestHandler\TemplateRequestHandler;
use Labstag\Search\TemplateSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/template")
 */
class TemplateController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_template_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_template_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Template $template,
        TemplateRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            TemplateType::class,
            !is_null($template) ? $template : new Template()
        );
    }

    /**
     * @Route("/trash", name="admin_template_trash", methods={"GET"})
     * @Route("/", name="admin_template_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(
        EntityManagerInterface $entityManager,
        TemplateRepository $repository
    ): Response
    {
        return $this->listOrTrash(
            $entityManager,
            $repository,
            'admin/template/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_template_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_template_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Template $template
    ): Response
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

    protected function searchForm(): array
    {
        return [
            'form' => SearchTemplateType::class,
            'data' => new TemplateSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminTemplace(): array
    {
        return [
            [
                'title'        => $this->translator->trans('template.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_template_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('template.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_template_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('template.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_template_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplacePreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('template.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_template_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('template.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_template_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('template.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_template_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminTemplaceTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('template.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_template_trash',
                'route_params' => [],
            ],
        ];
    }

    protected function setHeaderTitle(): array
    {
        $headers = parent::setHeaderTitle();

        return array_merge(
            $headers,
            [
                'admin_template' => $this->translator->trans('template.title', [], 'admin.header'),
            ]
        );
    }
}
