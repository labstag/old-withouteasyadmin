<?php

namespace Labstag\Service\Gestion\Entity;

use Labstag\Entity\Groupe;
use Labstag\Entity\Route;
use Labstag\Entity\User;
use Labstag\Entity\Workflow;
use Labstag\Interfaces\AdminEntityServiceInterface;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\RouteRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\Response;

class GuardService extends ViewService implements AdminEntityServiceInterface
{
    public function getType(): string
    {
        return 'guard';
    }

    public function global(): Response
    {
        /** @var WorkflowRepository $workflowRepository */
        $workflowRepository = $this->entityManager->getRepository(Workflow::class);
        /** @var GroupeRepository $groupeRepository */
        $groupeRepository = $this->entityManager->getRepository(Groupe::class);
        /** @var RouteRepository $routeRepository */
        $routeRepository = $this->entityManager->getRepository(Route::class);
        $workflows       = $workflowRepository->findBy(
            [],
            [
                'entity'     => 'ASC',
                'transition' => 'ASC',
            ]
        );

        return $this->render(
            'admin/guard/index.html.twig',
            [
                'groups'    => $groupeRepository->findBy([], ['name' => 'ASC']),
                'routes'    => $routeRepository->findBy([], ['name' => 'ASC']),
                'workflows' => $workflows,
            ]
        );
    }

    public function groupe(Groupe $groupe): Response
    {
        /** @var WorkflowRepository $workflowRepository */
        $workflowRepository = $this->entityManager->getRepository(Workflow::class);
        $this->btnService->addBtnList(
            'admin_groupuser_index',
            'Liste',
        );
        $this->btnService->addBtnShow(
            'admin_groupuser_show',
            'Show',
            [
                'id' => $groupe->getId(),
            ]
        );
        $this->btnService->addBtnEdit(
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

    public function user(User $user): Response
    {
        /** @var WorkflowRepository $workflowRepository */
        $workflowRepository = $this->entityManager->getRepository(Workflow::class);
        $this->btnService->addBtnList(
            'admin_user_index',
            'Liste',
        );
        $this->btnService->addBtnShow(
            'admin_user_show',
            'Show',
            [
                'id' => $user->getId(),
            ]
        );
        $this->btnService->addBtnEdit(
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
}
