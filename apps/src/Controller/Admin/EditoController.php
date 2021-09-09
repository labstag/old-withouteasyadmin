<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Edito;
use Labstag\Form\Admin\EditoType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\EditoRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\EditoRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/edito")
 */
class EditoController extends AdminControllerLib
{

    protected string $headerTitle = 'Edito';

    /**
     * @Route("/{id}/edit", name="admin_edito_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Edito $edito,
        EditoRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            EditoType::class,
            $edito,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_edito_index',
                'show'   => 'admin_edito_show',
            ],
            'admin/edito/form.html.twig'
        );
    }

    /**
     * @Route("/trash",  name="admin_edito_trash", methods={"GET"})
     * @Route("/",       name="admin_edito_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(EditoRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/edito/index.html.twig',
            [
                'new'   => 'admin_edito_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_edito_trash',
                'list'  => 'admin_edito_index',
            ],
            [
                'list'     => 'admin_edito_index',
                'show'     => 'admin_edito_show',
                'preview'  => 'admin_edito_preview',
                'edit'     => 'admin_edito_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_edito_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        EditoRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Edito(),
            EditoType::class,
            ['list' => 'admin_edito_index'],
            'admin/edito/form.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_edito_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_edito_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Edito $edito
    ): Response
    {
        return $this->renderShowOrPreview(
            $edito,
            'admin/edito/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_edito_edit',
                'list'    => 'admin_edito_index',
                'trash'   => 'admin_edito_trash',
            ]
        );
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
}
