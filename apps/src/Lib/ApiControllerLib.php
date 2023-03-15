<?php

namespace Labstag\Lib;

use Doctrine\ORM\EntityManagerInterface;
use Labstag\Entity\Route;
use Labstag\Entity\RouteUser;
use Labstag\Entity\User;
use Labstag\Entity\Workflow;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Repository\UserRepository;
use Labstag\Service\PhoneService;
use Labstag\Service\RepositoryService;
use Labstag\Service\WorkflowService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

abstract class ApiControllerLib extends AbstractController
{
    public function __construct(
        protected RepositoryService $repositoryService,
        protected RequestStack $requeststack,
        protected CsrfTokenManagerInterface $csrfTokenManager,
        protected TokenStorageInterface $tokenStorage,
        protected PhoneService $phoneService,
        protected EntityManagerInterface $entityManager,
        protected WorkflowService $workflowService,
        protected UserRepository $userRepository
    )
    {
    }

    protected function getGuardRouteOrWorkflow(array $data, array $get, string $entityClass): array
    {
        if (!array_key_exists('user', $get)) {
            return $data;
        }

        $data['user'] = [];
        $user         = $this->userRepository->find($get['user']);
        if (!$user instanceof User) {
            return $data;
        }

        /** @var ServiceEntityRepositoryLib $entityRepository */
        $entityRepository = $this->repositoryService->get($entityClass);
        $results          = $entityRepository->findEnableByUser($user);
        if (RouteUser::class == $entityClass) {
            if (!is_iterable($results)) {
                return $data;
            }

            foreach ($results as $row) {
                /** @var RouteUser $row */
                /** @var Route $route */
                $route          = $row->getRefroute();
                $data['user'][] = [
                    'route' => $route->getName(),
                ];
            }

            return $data;
        }

        if (!is_iterable($results)) {
            return $data;
        }

        foreach ($results as $result) {
            /** @var WorkflowGroupe $result */
            /** @var Workflow $workflow */
            $workflow        = $result->getRefworkflow();
            $data['group'][] = [
                'entity'     => $workflow->getEntity(),
                'transition' => $workflow->getTransition(),
            ];
        }

        return $data;
    }

    protected function getResultWorkflow(Request $request, string $entity): mixed
    {
        /** @var ServiceEntityRepositoryLib $userRepository */
        $userRepository = $this->repositoryService->get(User::class);
        /** @var ServiceEntityRepositoryLib $entityRepository */
        $entityRepository = $this->repositoryService->get($entity);
        $get              = $request->query->all();
        if (array_key_exists('user', $get)) {
            /** @var User $user */
            $user = $userRepository->find($get['user']);

            return $entityRepository->findEnableByGroupe($user->getRefgroupe());
        }

        return $entityRepository->findEnableByGroupe();
    }
}
