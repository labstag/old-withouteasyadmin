<?php

namespace Labstag\Controller\Admin\User;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Groupe;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Labstag\Repository\WorkflowRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/groupe', name: 'admin_groupuser_')]
class GroupeController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function edit(
        ?Groupe $groupe
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($groupe) ? new Groupe() : $groupe
        );
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

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/user/groupe/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function showOrPreview(Groupe $groupe): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $groupe,
            'admin/user/groupe/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        $domainLib = $this->domainService->getDomain(Groupe::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
