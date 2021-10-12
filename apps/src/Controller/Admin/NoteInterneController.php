<?php

namespace Labstag\Controller\Admin;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\NoteInterne;
use Labstag\Form\Admin\NoteInterneType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\NoteInterneRepository;
use Labstag\RequestHandler\NoteInterneRequestHandler;
use Labstag\Service\AttachFormService;
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
     * @Route("/new", name="admin_noteinterne_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?NoteInterne $noteInterne,
        NoteInterneRequestHandler $requestHandler
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->form(
            $service,
            $requestHandler,
            NoteInterneType::class,
            !is_null($noteInterne) ? $noteInterne : new NoteInterne(),
            $noteInterne,
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
            'admin/note_interne/index.html.twig',
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
            'admin/note_interne/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_noteinterne_edit',
            'empty'    => 'api_action_empty',
            'list'     => 'admin_noteinterne_index',
            'new'      => 'admin_noteinterne_new',
            'preview'  => 'admin_noteinterne_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_noteinterne_show',
            'trash'    => 'admin_noteinterne_trash',
            'workflow' => 'api_action_workflow',
        ];
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
