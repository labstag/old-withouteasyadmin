<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\User;
use Labstag\Form\Admin\User\UserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Reader\UploadAnnotationReader;
use Labstag\Repository\AttachmentRepository;
use Labstag\Repository\UserRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\RequestHandler\AttachmentRequestHandler;
use Labstag\RequestHandler\UserRequestHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     */
    public function edit(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        User $user,
        UserRequestHandler $requestHandler
    ): Response
    {
        return $this->update(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            UserType::class,
            $user,
            [
                'delete' => 'api_action_delete',
                'list'   => 'admin_user_index',
                'guard'  => 'admin_user_guard',
                'show'   => 'admin_user_show',
            ],
            'admin/user/form.html.twig'
        );
    }

    /**
     * @Route("/{id}/guard", name="admin_user_guard")
     */
    public function guard(
        User $user,
        WorkflowRepository $workflowRepo
    ): Response
    {
        $this->btnInstance()->addBtnList(
            'admin_user_index',
            'Liste',
        );
        $this->btnInstance()->addBtnShow(
            'admin_user_show',
            'Show',
            [
                'id' => $user->getId(),
            ]
        );

        $this->btnInstance()->addBtnEdit(
            'admin_user_edit',
            'Editer',
            [
                'id' => $user->getId(),
            ]
        );
        $routes = $this->guardService->getGuardRoutesForUser($user);
        if (0 == count($routes)) {
            $this->flashBagAdd(
                'danger',
                $this->translator->trans('admin.user.guard.superadmin.nope')
            );

            return $this->redirectToRoute('admin_user_index');
        }

        return $this->render(
            'admin/guard/user.html.twig',
            [
                'user'      => $user,
                'routes'    => $routes,
                'workflows' => $workflowRepo->findBy([], ['entity' => 'ASC', 'transition' => 'ASC']),
            ]
        );
    }

    /**
     * @Route("/trash",  name="admin_user_trash", methods={"GET"})
     * @Route("/",       name="admin_user_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(UserRepository $repository): Response
    {
        return $this->listOrTrash(
            $repository,
            [
                'trash' => 'findTrashForAdmin',
                'all'   => 'findAllForAdmin',
            ],
            'admin/user/index.html.twig',
            [
                'new'   => 'admin_user_new',
                'empty' => 'api_action_empty',
                'trash' => 'admin_user_trash',
                'list'  => 'admin_user_index',
            ],
            [
                'list'     => 'admin_user_index',
                'show'     => 'admin_user_show',
                'preview'  => 'admin_user_preview',
                'edit'     => 'admin_user_edit',
                'delete'   => 'api_action_delete',
                'destroy'  => 'api_action_destroy',
                'guard'    => 'admin_user_guard',
                'restore'  => 'api_action_restore',
                'workflow' => 'api_action_workflow',
            ]
        );
    }

    /**
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function new(
        UploadAnnotationReader $uploadAnnotReader,
        AttachmentRepository $attachmentRepository,
        AttachmentRequestHandler $attachmentRH,
        UserRequestHandler $requestHandler
    ): Response
    {
        $user = new User();

        return $this->create(
            $uploadAnnotReader,
            $attachmentRepository,
            $attachmentRH,
            $requestHandler,
            $user,
            UserType::class,
            ['list' => 'admin_user_index'],
            'admin/user/form.html.twig'
        );
    }

    /**
     * @Route("/{id}",         name="admin_user_show", methods={"GET"})
     * @Route("/preview/{id}", name="admin_user_preview", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function showOrPreview(
        User $user
    ): Response
    {
        $this->modalAttachmentDelete();

        return $this->renderShowOrPreview(
            $user,
            'admin/user/show.html.twig',
            [
                'delete'  => 'api_action_delete',
                'restore' => 'api_action_restore',
                'destroy' => 'api_action_destroy',
                'list'    => 'admin_user_index',
                'guard'   => 'admin_user_guard',
                'edit'    => 'admin_user_edit',
                'trash'   => 'admin_user_trash',
            ]
        );
    }

    protected function setBreadcrumbsPageAdminUser(): array
    {
        return [
            [
                'title'        => $this->translator->trans('user.title', [], 'admin.breadcrumb'),
                'route'        => 'admin_user_index',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserEdit(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('user.edit', [], 'admin.breadcrumb'),
                'route'        => 'admin_user_edit',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserGuard(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('user.guard', [], 'admin.breadcrumb'),
                'route'        => 'admin_user_guard',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserNew(): array
    {
        return [
            [
                'title'        => $this->translator->trans('user.new', [], 'admin.breadcrumb'),
                'route'        => 'admin_user_new',
                'route_params' => [],
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserPreview(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('user.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_user_trash',
                'route_params' => [],
            ],
            [
                'title'        => $this->translator->trans('user.preview', [], 'admin.breadcrumb'),
                'route'        => 'admin_user_preview',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserShow(): array
    {
        $request     = $this->get('request_stack')->getCurrentRequest();
        $all         = $request->attributes->all();
        $routeParams = $all['_route_params'];

        return [
            [
                'title'        => $this->translator->trans('user.show', [], 'admin.breadcrumb'),
                'route'        => 'admin_user_show',
                'route_params' => $routeParams,
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserTrash(): array
    {
        return [
            [
                'title'        => $this->translator->trans('user.trash', [], 'admin.breadcrumb'),
                'route'        => 'admin_user_trash',
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
                'admin_user' => $this->translator->trans('user.title', [], 'admin.header'),
            ]
        );
    }
}
