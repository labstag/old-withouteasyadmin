<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\User;
use Labstag\Entity\Workflow;
use Labstag\Form\Admin\Search\UserType as SearchUserType;
use Labstag\Form\Admin\User\UserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Search\UserSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 */
class UserController extends AdminControllerLib
{
    /**
     * @Route("/{id}/edit", name="admin_user_edit", methods={"GET","POST"})
     * @Route("/new", name="admin_user_new", methods={"GET","POST"})
     */
    public function edit(
        AttachFormService $service,
        ?User $user,
        UserRequestHandler $requestHandler
    ): Response
    {
        return $this->form(
            $service,
            $requestHandler,
            UserType::class,
            !is_null($user) ? $user : new User(),
            'admin/user/form.html.twig'
        );
    }

    public function getUrlAdmin(): array
    {
        return [
            'delete'   => 'api_action_delete',
            'destroy'  => 'api_action_destroy',
            'edit'     => 'admin_user_edit',
            'empty'    => 'api_action_empty',
            'guard'    => 'admin_user_guard',
            'list'     => 'admin_user_index',
            'new'      => 'admin_user_new',
            'preview'  => 'admin_user_preview',
            'restore'  => 'api_action_restore',
            'show'     => 'admin_user_show',
            'trash'    => 'admin_user_trash',
            'workflow' => 'api_action_workflow',
        ];
    }

    /**
     * @Route("/{id}/guard", name="admin_user_guard")
     */
    public function guard(
        User $user
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

        $workflows = $this->getRepository(Workflow::class)->findBy(
            [],
            [
                'entity'     => 'ASC',
                'transition' => 'ASC',
            ]
        );

        return $this->render(
            'admin/guard/user.html.twig',
            [
                'user'      => $user,
                'routes'    => $routes,
                'workflows' => $workflows,
            ]
        );
    }

    /**
     * @Route("/trash", name="admin_user_trash", methods={"GET"})
     * @Route("/", name="admin_user_index", methods={"GET"})
     * @IgnoreSoftDelete
     */
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            User::class,
            'admin/user/index.html.twig'
        );
    }

    /**
     * @Route("/{id}", name="admin_user_show", methods={"GET"})
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
            'admin/user/show.html.twig'
        );
    }

    protected function searchForm(): array
    {
        return [
            'form' => SearchUserType::class,
            'data' => new UserSearch(),
        ];
    }

    protected function setBreadcrumbsPageAdminUser(): array
    {
        return [
            [
                'title' => $this->translator->trans('user.title', [], 'admin.breadcrumb'),
                'route' => 'admin_user_index',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('user.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_user_edit',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserGuard(): array
    {
        return [
            [
                'title' => $this->translator->trans('user.guard', [], 'admin.breadcrumb'),
                'route' => 'admin_user_guard',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserNew(): array
    {
        return [
            [
                'title' => $this->translator->trans('user.new', [], 'admin.breadcrumb'),
                'route' => 'admin_user_new',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserPreview(): array
    {
        return [
            [
                'title' => $this->translator->trans('user.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_user_trash',
            ],
            [
                'title' => $this->translator->trans('user.preview', [], 'admin.breadcrumb'),
                'route' => 'admin_user_preview',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserShow(): array
    {
        return [
            [
                'title' => $this->translator->trans('user.show', [], 'admin.breadcrumb'),
                'route' => 'admin_user_show',
            ],
        ];
    }

    protected function setBreadcrumbsPageAdminUserTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('user.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_user_trash',
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
