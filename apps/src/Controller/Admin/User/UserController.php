<?php

namespace Labstag\Controller\Admin\User;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\User;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Labstag\Repository\WorkflowRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/admin/user')]
class UserController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_user_edit', methods: ['GET', 'POST'])]
    #[Route(path: '/new', name: 'admin_user_new', methods: ['GET', 'POST'])]
    public function edit(
        ?User $user
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
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
        $this->adminBtnService->addBtnList(
            'admin_user_index',
            'Liste',
        );
        $this->adminBtnService->addBtnShow(
            'admin_user_show',
            'Show',
            [
                'id' => $user->getId(),
            ]
        );
        $this->adminBtnService->addBtnEdit(
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

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_user_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_user_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/user/index.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_user_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_user_preview', methods: ['GET'])]
    public function showOrPreview(User $user): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $user,
            'admin/user/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        $domainLib = $this->domainService->getDomain(User::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
