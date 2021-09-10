<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Groupe;
use Labstag\Form\Admin\User\GroupeType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\GroupeRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user/groupe")
 */
class GroupeController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_groupuser_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        Groupe $groupe,
        GroupeRequestHandler $requestHandler
    ): Response
    {
        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            GroupeType::class,
            $groupe,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_groupuser_index',
                'guard'  => 'admin_groupuser_guard',
                'show'   => 'admin_groupuser_show',
            ]
        );
    }

    /**
     * @Route("/{id}/guard", name="admin_groupuser_guard")
     */
    public function guard(
        Groupe $groupe,
        WorkflowRepository $workflowRepo
    ): Response
    {
        $this->btnInstance()->addBtnList(
            'admin_groupuser_index',
            'Liste',
        );
        $this->btnInstance()->addBtnShow(
            'admin_groupuser_show',
            'Show',
            [
                'id' => $groupe->getId(),
            ]
        );

        $this->btnInstance()->addBtnEdit(
            'admin_groupuser_edit',
            'Editer',
            [
                'id' => $groupe->getId(),
            ]
        );

        $routes = $this->guardService->getGuardRoutesForGroupe($groupe);
        if (0 == count($routes)) {
            $this->flashBagAdd(
                'danger',
                $this->translator->trans('admin.group.guard.superadmin.nope')
            );

            return $this->redirectToRoute('admin_groupuser_index');
        }

        return $this->render(
            'admin/user/guard/group.html.twig',
            [
                'group'     => $groupe,
                'routes'    => $routes,
                'workflows' => $workflowRepo->findBy([], ['entity' => 'ASC', 'transition' => 'ASC']),
            ]
        );
    }

    /**
     * @Route("/trash",  name="admin_groupuser_trash", methods={"GET"})
     * @Route("/",       name="admin_groupuser_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function index(GroupeRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/user/groupe/index.html.twig',
            [
                'new'   => 'admin_groupuser_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_groupuser_trash',
                'list'  => 'admin_groupuser_index',
            ],
            [
                'list'    => 'admin_groupuser_index',
                'show'    => 'admin_groupuser_show',
                'edit'    => 'admin_groupuser_edit',
                'preview' => 'admin_groupuser_preview',
                'delete'  => 'api_action_delete',
                'guard'   => 'admin_groupuser_guard',
                'destroy' => 'api_action_destroy',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_groupuser_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        GroupeRequestHandler $requestHandler
    ): Response
    {
        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            new Groupe(),
            GroupeType::class,
            ['list' => 'admin_groupuser_index']
        );
    }

    /**
     * @Route("/{id}",         name="admin_groupuser_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_groupuser_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        Groupe $groupe
    ): Response
    {
        return $this->renderShowOrPreview(
            $groupe,
            'admin/user/groupe/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'edit'    => 'admin_groupuser_edit',
                'guard'   => 'admin_groupuser_guard',
                'list'    => 'admin_groupuser_index',
                'trash'   => 'admin_groupuser_trash',
            ]
        );
    }

    protected function setBreadcrumbsPageAdminGroupuser(): array
    {
        return [
            [
                'title'        => $this->translator->trans('groupuser.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_groupuser_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGroupuserEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('groupuser.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_groupuser_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGroupuserGuard(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('groupuser.guard', [], 'admin.breadcrumb'),
                'route'        => 'admin_groupuser_guard',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGroupuserNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('groupuser.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_groupuser_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGroupuserPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('groupuser.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_groupuser_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('groupuser.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_groupuser_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGroupuserShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('groupuser.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_groupuser_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminGroupuserTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('groupuser.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_groupuser_trash',
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
                'admin_groupuser' => $this->translator->trans('groupuser.title', [], 'admin.header'),
            ]
        );
    }
}
