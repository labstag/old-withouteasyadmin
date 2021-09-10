<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\NoteInterne;
use Labstag\Form\Admin\NoteInterneType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\NoteInterneRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\NoteInterneRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/noteinterne")
 */
class NoteInterneController extends AdminControllerLib
{
    /**
     * @Route(
     *  "/{id}/edit",
     *  name="admin_noteinterne_edit",
     *  methods={"GET","POST"}
     * )
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        NoteInterne $noteInterne,
        NoteInterneRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            NoteInterneType::class,
            $noteInterne,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_noteinterne_index',
                'show'   => 'admin_noteinterne_show',
            ],
            'admin/note_interne/form.html.twig'
        );
    }

    /**
     * @Route("/trash",  name="admin_noteinterne_trash", methods={"GET"})
     * @Route("/",       name="admin_noteinterne_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(NoteInterneRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/note_interne/index.html.twig',
            [
                'new'   => 'admin_noteinterne_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_noteinterne_trash',
                'list'  => 'admin_noteinterne_index',
            ],
            [
                'list'     => 'admin_noteinterne_index',
                'show'     => 'admin_noteinterne_show',
                'preview'  => 'admin_noteinterne_preview',
                'edit'     => 'admin_noteinterne_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_noteinterne_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        NoteInterneRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new NoteInterne(),
            NoteInterneType::class,
            ['list' => 'admin_noteinterne_index'],
            'admin/note_interne/form.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_noteinterne_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_noteinterne_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        NoteInterne $noteInterne
    ): Response
    {
        return $this->renderShowOrPreview(
            $noteInterne,
            'admin/note_interne/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'list'    => 'admin_noteinterne_index',
                'edit'    => 'admin_noteinterne_edit',
                'trash'   => 'admin_noteinterne_trash',
            ]
        );
    }

    protected function setBreadcrumbsPageAdminNoteinterne(): array
    {
        return [
            [
                'title'        => $this->translator->trans('noteinterne.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_noteinterne_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('noteinterne.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_noteinterne_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('noteinterne.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_noteinterne_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinternePreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('noteinterne.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_noteinterne_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('noteinterne.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_noteinterne_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('noteinterne.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_noteinterne_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminNoteinterneTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('noteinterne.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_noteinterne_trash',
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
                'admin_noteinterne' => $this->translator->trans('noteinterne.title', [], 'admin.header'),
            ]
        );
    }
}
