<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Libelle;
use Labstag\Form\Admin\LibelleType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\LibelleRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/libelle")
 */
class LibelleController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_libelle_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Libelle $libelle,
        LibelleRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            LibelleType::class,
            $libelle,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_libelle_index',
                'show'   => 'admin_libelle_show',
            ]
        );
    }

    /**
     * @Route("/trash",  name="admin_libelle_trash", methods={"GET"})
     * @Route("/",       name="admin_libelle_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(LibelleRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/libelle/index.html.twig',
            [
                'new'   => 'admin_libelle_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_libelle_trash',
                'list'  => 'admin_libelle_index',
            ],
            [
                'list'     => 'admin_libelle_index',
                'show'     => 'admin_libelle_show',
                'preview'  => 'admin_libelle_preview',
                'edit'     => 'admin_libelle_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_libelle_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        LibelleRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Libelle(),
            LibelleType::class,
            ['list' => 'admin_libelle_index']
        );
    }

    /**
     * @Route("/{id}",         name="admin_libelle_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_libelle_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Libelle $libelle
    ): Response
    {
        return $this->renderShowOrPreview(
            $libelle,
            'admin/libelle/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_libelle_edit',
                'list'    => 'admin_libelle_index',
                'trash'   => 'admin_libelle_trash',
            ]
        );
    }

    protected function setBreadcrumbsPageAdminlibelle(): array
    {
        return [
            [
                'title'        => $this->translator->trans('libelle.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_libelle_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibelleEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('libelle.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_libelle_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibelleNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('libelle.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_libelle_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibellePreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('libelle.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_libelle_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('libelle.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_libelle_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibelleShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('libelle.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_libelle_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminlibelleTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('libelle.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_libelle_trash',
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
                'admin_libelle' => $this->translator->trans('libelle.title', [], 'admin.header'),
            ]
        );
    }
}
