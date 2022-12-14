<?php

namespace Labstag\Controller\Admin\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Groupe;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\WorkflowRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user/groupe')]
class GroupeController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_groupuser_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_groupuser_new', methods: ['GET', 'POST'])]
    public function edit(
        ?Groupe $groupe
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
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
            $this->getDomainEntity(),
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
            $this->getDomainEntity(),
            $groupe,
            'admin/user/groupe/show.html.twig'
        );
    }

    protected function getDomainEntity()
    {
        return $this->domainService->getDomain(Groupe::class);
    }
}
