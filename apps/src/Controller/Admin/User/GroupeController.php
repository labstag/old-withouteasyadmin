<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Groupe;
use Labstag\Entity\Workflow;
use Labstag\Form\Admin\Search\GroupeType as SearchGroupeType;
use Labstag\Form\Admin\User\GroupeType;
use Labstag\Lib\AdminControllerLib;
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
    public function edit(AttachFormService $service, ?Groupe $groupe, GroupeRequestHandler $requestHandler) : Response
    {
        return $this->form(
            $service,
            $requestHandler,
            GroupeType::class,
            !is_null($groupe) ? $groupe : new Groupe()
        );
    }
    #[Route(path: '/{id}/guard', name: 'admin_groupuser_guard')]
    public function guard(Groupe $groupe) : Response
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
        $workflows = $this->getRepository(Workflow::class)->findBy(
            [],
            [
                'entity'     => 'ASC',
                'transition' => 'ASC',
            ]
        );
        return $this->render(
            'admin/user/guard/group.html.twig',
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
    public function index() : Response
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
    public function showOrPreview(Groupe $groupe) : Response
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
    protected function searchForm(): array
    {
        return [
            'form' => SearchGroupeType::class,
            'data' => new GroupeSearch(),
        ];
    }
    protected function setBreadcrumbsPageAdminGroupuser(): array
    {
        return [
            [
                'title' => $this->translator->trans('groupuser.title', [], 'admin.breadcrumb'),
                'route' => 'admin_groupuser_index',
            ],
        ];
    }
    protected function setBreadcrumbsPageAdminGroupuserEdit(): array
    {
        return [
            [
                'title' => $this->translator->trans('groupuser.edit', [], 'admin.breadcrumb'),
                'route' => 'admin_groupuser_edit',
            ],
        ];
    }
    protected function setBreadcrumbsPageAdminGroupuserGuard(): array
    {
        return [
            [
                'title' => $this->translator->trans('groupuser.guard', [], 'admin.breadcrumb'),
                'route' => 'admin_groupuser_guard',
            ],
        ];
    }
    protected function setBreadcrumbsPageAdminGroupuserNew(): array
    {
        return [
            [
                'title' => $this->translator->trans('groupuser.new', [], 'admin.breadcrumb'),
                'route' => 'admin_groupuser_new',
            ],
        ];
    }
    protected function setBreadcrumbsPageAdminGroupuserPreview(): array
    {
        return [
            [
                'title' => $this->translator->trans('groupuser.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_groupuser_trash',
            ],
            [
                'title' => $this->translator->trans('groupuser.preview', [], 'admin.breadcrumb'),
                'route' => 'admin_groupuser_preview',
            ],
        ];
    }
    protected function setBreadcrumbsPageAdminGroupuserShow(): array
    {
        return [
            [
                'title' => $this->translator->trans('groupuser.show', [], 'admin.breadcrumb'),
                'route' => 'admin_groupuser_show',
            ],
        ];
    }
    protected function setBreadcrumbsPageAdminGroupuserTrash(): array
    {
        return [
            [
                'title' => $this->translator->trans('groupuser.trash', [], 'admin.breadcrumb'),
                'route' => 'admin_groupuser_trash',
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
