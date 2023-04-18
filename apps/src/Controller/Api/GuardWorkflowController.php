<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Entity\Workflow;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Entity\WorkflowUser;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\UserRepository;
use Labstag\Repository\WorkflowGroupeRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\Repository\WorkflowUserRepository;
use Labstag\Service\RepositoryService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/guard/workflow', name: 'api_guard_')]
class GuardWorkflowController extends ApiControllerLib
{
    #[Route(path: '/group/{group}', name: 'workflowgroup', methods: ['POST'])]
    public function group(
        WorkflowUserRepository $workflowUserRepository,
        WorkflowRepository $workflowRepository,
        Request $request,
        Groupe $groupe
    ): JsonResponse
    {
        return $this->setWorkflow(
            $workflowUserRepository,
            $workflowRepository,
            $request,
            $groupe
        );
    }

    #[Route(path: '/groups/{workflow}', name: 'workflowgroups', methods: ['POST'])]
    public function groups(
        WorkflowGroupeRepository $workflowGroupeRepository,
        Workflow $workflow,
        Request $request,
        GroupeRepository $groupeRepository
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state   = $request->request->all('state');
        $groupes = $groupeRepository->findAll();
        foreach ($groupes as $groupe) {
            /** @var Groupe $groupe */
            $data = $this->setWorkflowGroupe(
                $workflowGroupeRepository,
                $data,
                $groupe,
                $workflow,
                (bool) $state
            );
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/', name: 'workflow')]
    public function index(
        UserRepository $userRepository,
        RepositoryService $repositoryService,
        Request $request
    ): JsonResponse
    {
        $data = [
            'group' => [],
        ];
        $get  = $request->query->all();
        $data = $this->getGuardRouteOrWorkflow(
            $repositoryService,
            $userRepository,
            $data,
            $get,
            WorkflowUser::class
        );
        $results = $this->getResultWorkflow($repositoryService, $request, WorkflowGroupe::class);
        if (!is_iterable($results)) {
            return new JsonResponse($data);
        }

        foreach ($results as $result) {
            /** @var WorkflowGroupe $result */
            /** @var Groupe $groupe */
            $groupe = $result->getRefgroupe();
            /** @var Workflow $workflow */
            $workflow        = $result->getRefworkflow();
            $data['group'][] = [
                'groupe'     => $groupe->getCode(),
                'entity'     => $workflow->getEntity(),
                'transition' => $workflow->getTransition(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/setgroup/{group}/{workflow}', name: 'workflowsetgroup', methods: ['POST'])]
    public function setgroup(
        WorkflowGroupeRepository $workflowGroupeRepository,
        Groupe $groupe,
        Workflow $workflow,
        Request $request
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state = $request->request->all('state');
        $data  = $this->setWorkflowGroupe(
            $workflowGroupeRepository,
            $data,
            $groupe,
            $workflow,
            (bool) $state
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/setuser/{user}/{workflow}', name: 'workflowsetuser', methods: ['POST'])]
    public function setuser(
        WorkflowUserRepository $workflowUserRepository,
        User $user,
        Workflow $workflow,
        Request $request
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state = $request->request->all('state');
        $data  = $this->setWorkflowUser(
            $workflowUserRepository,
            $data,
            $user,
            $workflow,
            (bool) $state
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/user/{user}', name: 'workflowuser', methods: ['POST'])]
    public function user(
        WorkflowUserRepository $workflowUserRepository,
        WorkflowRepository $workflowRepository,
        User $user,
        Request $request
    ): JsonResponse
    {
        return $this->setWorkflow(
            $workflowUserRepository,
            $workflowRepository,
            $request,
            $user
        );
    }

    private function setWorkflow(
        WorkflowUserRepository $workflowUserRepository,
        WorkflowRepository $workflowRepository,
        Request $request,
        mixed $entity
    ): JsonResponse
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state     = $request->request->all('state');
        $workflows = $workflowRepository->findAll();
        foreach ($workflows as $workflow) {
            /** @var Workflow $workflow */
            $data = $this->setWorkflowUser(
                $workflowUserRepository,
                $data,
                $entity,
                $workflow,
                (bool) $state
            );
        }

        return new JsonResponse($data);
    }

    private function setWorkflowGroupe(
        WorkflowGroupeRepository $workflowGroupeRepository,
        array $data,
        Groupe $groupe,
        Workflow $workflow,
        bool $state
    ): array
    {
        $workflowGroupe = $workflowGroupeRepository->findOneBy(
            [
                'refgroupe'   => $groupe,
                'refworkflow' => $workflow,
            ]
        );
        if (false === $state) {
            if ($workflowGroupe instanceof WorkflowGroupe) {
                $data['delete'] = 1;
                $workflowGroupeRepository->remove($workflowGroupe);
            }

            return $data;
        }

        if ('superadmin' === $groupe->getCode()) {
            return $data;
        }

        if (!$workflowGroupe instanceof WorkflowGroupe) {
            $workflowGroupe = new WorkflowGroupe();
            $data['add']    = 1;
            $workflowGroupe->setRefgroupe($groupe);
            $workflowGroupe->setRefworkflow($workflow);
            $workflowGroupe->setState($state);
            $workflowGroupeRepository->save($workflowGroupe);
        }

        return $data;
    }

    private function setWorkflowUser(
        WorkflowUserRepository $workflowUserRepository,
        array $data,
        ?User $user,
        ?Workflow $workflow,
        bool $state
    ): array
    {
        $workflowUser = $workflowUserRepository->findOneBy(['refuser' => $user, 'refworkflow' => $workflow]);
        if (false === $state) {
            if ($workflowUser instanceof WorkflowUser) {
                $data['delete'] = 1;
                $workflowUserRepository->remove($workflowUser);
            }

            return $data;
        }

        /** @var Groupe $groupe */
        /** @var User $user */
        $groupe = $user->getRefgroupe();
        if ('superadmin' === $groupe->getCode()) {
            return $data;
        }

        if (!$workflowUser instanceof WorkflowUser) {
            $data['add']  = 1;
            $workflowUser = new WorkflowUser();
            $workflowUser->setRefuser($user);
            $workflowUser->setRefworkflow($workflow);
            $workflowUser->setState($state);
            $workflowUserRepository->save($workflowUser);
        }

        return $data;
    }
}
