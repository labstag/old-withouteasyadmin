<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Form\Admin\Search\EditoType as SearchEditoType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\RequestHandler\EditoRequestHandler;
use Labstag\Search\EditoSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/edito")
 */
class EditoController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_edito_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_edito_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?Edito $edito,
        EditoRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            EditoType::class,
            !is_null($edito) ? $edito : new Edito(),
            'admin/edito/form.html.twig'
        );
    }

    /**
     * @Route("/trash", name="admin_edito_trash", methods={"GET"})
     * @Route("/", name="admin_edito_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(EditoRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            'admin/edito/index.html.twig',
        );
    }

    /**
     * @Route("/{id}", name="admin_edito_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_edito_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Edito $edito
    ): Response
    {
        return $this->renderShowOrPreview(
            $edito,
            'admin/edito/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_edito_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_edito_index',
            'new'      => 'admin_edito_new',
            'preview'  => 'admin_edito_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_edito_show',
            'trash'    => 'admin_edito_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchEditoType::class,
            'data' => new EditoSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminEdito(): array
    {
        return [
            [
                'title'        => $this->translator->trans('edito.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_edito_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminEditoEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('edito.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_edito_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminEditoNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('edito.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_edito_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminEditoPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('edito.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_edito_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('edito.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_edito_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminEditoShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('edito.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_edito_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminEditoTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('edito.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_edito_trash',
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
                'admin_edito' => $this->translator->trans('edito.title', [], 'admin.header'),
            ]
        );
    }
}
