<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Groupe;
use Labstag\Form\Admin\Search\GroupeType as SearchGroupeType;
use Labstag\Form\Admin\User\GroupeType;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\WorkflowRepository;
use Labstag\RequestHandler\GroupeRequestHandler;
use Labstag\Search\GroupeSearch;
use Labstag\Service\AttachFormService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/groupe')]
class GroupeController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_groupuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_groupuser_new', methods: ['GET', 'POST'])]
    public function edit(
        AttachFormService $attachFormService,
        ?Groupe $groupe,
        GroupeRequestHandler $groupeRequestHandler
    ): Response
    {
        return $this->form(
            $attachFormService,
            $groupeRequestHandler,
            GroupeType::class,
            is_null($groupe) ? new Groupe() : $groupe
        );
    }

    #[Route(path: '/{id}/guard', name: 'admin_groupuser_guard')]
    public function guard(
        Groupe $groupe,
        WorkflowRepository $workflowRepository
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
            $this->sessionService->flashBagAdd(
                'danger',
                $this->translator->trans('admin.group.guard.superadmin.nope')
            );

            return $this->redirectToRoute('admin_groupuser_index');
        }

        $workflows = $workflowRepository->findBy(
            [],
            [
                'entity'     => 'ASC',
                'transition' => 'ASC',
            ]
        );

        return $this->render(
            'admin/guard/group.html.twig',
            [
                'group'     => $groupe,
                'routes'    => $routes,
                'workflows' => $workflows,
            ]
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/trash', name: 'admin_groupuser_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_groupuser_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->listOrTrash(
            Groupe::class,
            'admin/user/groupe/index.html.twig'
        );
    }

    /**
     * @IgnoreSoftDelete
     */
    #[Route(path: '/{id}', name: 'admin_groupuser_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_groupuser_preview', methods: ['GET'])]
    public function showOrPreview(Groupe $groupe): Response
    {
        return $this->renderShowOrPreview(
            $groupe,
            'admin/user/groupe/show.html.twig'
        );
    }

    protected function getUrlAdmin(): array
    {
        return [
            'delete'  => 'api_action_delete',
            'destroy' => 'api_action_destroy',
            'edit'    => 'admin_groupuser_edit',
            'empty'   => 'api_action_empty',
            'guard'   => 'admin_groupuser_guard',
            'list'    => 'admin_groupuser_index',
            'new'     => 'admin_groupuser_new',
            'preview' => 'admin_groupuser_preview',
            'restore' => 'api_action_restore',
            'show'    => 'admin_groupuser_show',
            'trash'   => 'admin_groupuser_trash',
        ];
    }

    /**
     * @return array<string, \GroupeSearch>|array<string, class-string<\Labstag\Form\Admin\Search\GroupeType>>
     */
    protected function searchForm(): array
    {
        return [
            'form' => SearchGroupeType::class,
            'data' => new GroupeSearch(),
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
                    'title' => $this->translator->trans('groupuser.title', [], 'admin.breadcrumb'),
                    'route' => 'admin_groupuser_index',
                ],
                [
                    'title' => $this->translator->trans('groupuser.edit', [], 'admin.breadcrumb'),
                    'route' => 'admin_groupuser_edit',
                ],
                [
                    'title' => $this->translator->trans('groupuser.guard', [], 'admin.breadcrumb'),
                    'route' => 'admin_groupuser_guard',
                ],
                [
                    'title' => $this->translator->trans('groupuser.new', [], 'admin.breadcrumb'),
                    'route' => 'admin_groupuser_new',
                ],
                [
                    'title' => $this->translator->trans('groupuser.trash', [], 'admin.breadcrumb'),
                    'route' => 'admin_groupuser_trash',
                ],
                [
                    'title' => $this->translator->trans('groupuser.preview', [], 'admin.breadcrumb'),
                    'route' => 'admin_groupuser_preview',
                ],
                [
                    'title' => $this->translator->trans('groupuser.show', [], 'admin.breadcrumb'),
                    'route' => 'admin_groupuser_show',
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
                'admin_groupuser' => $this->translator->trans('groupuser.title', [], 'admin.header'),
            ],
        ];
    }
}
