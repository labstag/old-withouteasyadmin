<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\User;
use Labstag\Form\Admin\Search\UserType as SearchUserType;
use Labstag\Form\Admin\User\UserType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\WorkflowRepository;
use Labstag\RequestHandler\UserRequestHandler;
use Labstag\Search\UserSearch;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user')]
class UserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function edit(
        ?User $user,
        UserRequestHandler $userRequestHandler
    ): Response
    {
        return $this->form(
            $userRequestHandler,
            UserType::class,
            is_null($user) ? new User() : $user,
            'admin/user/form.html.twig'
        );
    }

    #[Route(path: '/{id}/guard', name: 'admin_user_guard')]
    public function guard(
        User $user,
        WorkflowRepository $workflowRepository
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
            $this->sessionService->flashBagAdd(
                'danger',
                $this->translator->trans('admin.user.guard.superadmin.nope')
            );

            return $this->redirectToRoute('admin_user_index');
        }

        $workflows = $workflowRepository->findBy(
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
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_user_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_user_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            User::class,
            'admin/user/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_user_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_user_preview', methods: ['GET'])]
    public function showOrPreview(User $user): Response
    {
        $this->modalAttachmentDelete();

        return $this->renderShowOrPreview(
            $user,
            'admin/user/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
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
     * @return array<string, \UserSearch>|array<string, class-string<\Labstag\Form\Admin\Search\UserType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchUserType::class,
            'data' => new UserSearch(),
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
                    'title' => $this->translator->trans('user.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_index',
                ],
                [
                    'title' => $this->translator->trans('user.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_edit',
                ],
                [
                    'title' => $this->translator->trans('user.guard', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_guard',
                ],
                [
                    'title' => $this->translator->trans('user.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_new',
                ],
                [
                    'title' => $this->translator->trans('user.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_trash',
                ],
                [
                    'title' => $this->translator->trans('user.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_preview',
                ],
                [
                    'title' => $this->translator->trans('user.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_user_show',
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

        return [
            ...$headers, ...
            [
                'admin_user' => $this->translator->trans('user.title', [], 'admin.header'),
            ],
        ];
    }
}
