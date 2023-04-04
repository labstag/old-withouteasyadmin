<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Groupe;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\WorkflowRepository;
use Labstag\Service\AdminService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/groupe', name: 'admin_groupuser_')]
class GroupeController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Groupe $groupe
    ): Response
    {
        return $this->setAdmin()->edit($groupe);
    }

    #[Route(path: '/{id}/guard', name: 'guard')]
    public function guard(
        Groupe $groupe,
        WorkflowRepository $workflowRepository
    ): Response
    {
        $this->adminBtnService->addBtnList(
            'admin_groupuser_index',
            'Liste',
        );
        $this->adminBtnService->addBtnShow(
            'admin_groupuser_show',
            'Show',
            [
                'id' => $groupe->getId(),
            ]
        );
        $this->adminBtnService->addBtnEdit(
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

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->setAdmin()->new();
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Groupe $groupe): Response
    {
        return $this->setAdmin()->preview($groupe);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Groupe $groupe): Response
    {
        return $this->setAdmin()->show($groupe);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): AdminService
    {
        $this->adminService->setDomain(Groupe::class);

        return $this->adminService;
    }
}
