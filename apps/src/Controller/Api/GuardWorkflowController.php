<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Entity\Workflow;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Entity\WorkflowUser;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\GroupeRepository;
use Labstag\Repository\WorkflowGroupeRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\Repository\WorkflowUserRepository;
use Labstag\RequestHandler\WorkflowGroupeRequestHandler;
use Labstag\RequestHandler\WorkflowUserRequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/api/guard/workflow')]
class GuardWorkflowController extends ApiControllerLib
{
    #[Route(path: '/group/{group}', name: 'api_guard_workflowgroup', methods: ['POST'])]
    public function group(
        WorkflowUserRepository $workflowUserRepository,
        WorkflowRepository $workflowRepository,
        Request $request,
        Groupe $groupe,
        WorkflowUserRequestHandler $workflowUserRequestHandler
    ): JsonResponse {
        return $this->setWorkflow(
            $workflowUserRepository,
            $workflowRepository,
            $request,
            $groupe,
            $workflowUserRequestHandler
        );
    }

    #[Route(path: '/groups/{workflow}', name: 'api_guard_workflowgroups', methods: ['POST'])]
    public function groups(
        WorkflowGroupeRepository $workflowGroupeRepository,
        Workflow $workflow,
        WorkflowGroupeRequestHandler $workflowGroupeRequestHandler,
        Request $request,
        GroupeRepository $groupeRepository
    ): JsonResponse {
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
                (bool) $state,
                $workflowGroupeRequestHandler
            );
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/', name: 'api_guard_workflow')]
    public function index(Request $request): JsonResponse
    {
        $data = [
            'group' => [],
        ];
        $get     = $request->query->all();
        $data    = $this->getGuardRouteOrWorkflow($data, $get, WorkflowUser::class);
        $results = $this->getResultWorkflow($request, WorkflowGroupe::class);
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

    #[Route(path: '/setgroup/{group}/{workflow}', name: 'api_guard_workflowsetgroup', methods: ['POST'])]
    public function setgroup(
        WorkflowGroupeRepository $workflowGroupeRepository,
        Groupe $groupe,
        Workflow $workflow,
        Request $request,
        WorkflowGroupeRequestHandler $workflowGroupeRequestHandler
    ): JsonResponse {
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
            (bool) $state,
            $workflowGroupeRequestHandler
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/setuser/{user}/{workflow}', name: 'api_guard_workflowsetuser', methods: ['POST'])]
    public function setuser(
        WorkflowUserRepository $workflowUserRepository,
        User $user,
        Workflow $workflow,
        Request $request,
        WorkflowUserRequestHandler $workflowUserRequestHandler
    ): JsonResponse {
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
            (bool) $state,
            $workflowUserRequestHandler
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/user/{user}', name: 'api_guard_workflowuser', methods: ['POST'])]
    public function user(
        WorkflowUserRepository $workflowUserRepository,
        WorkflowRepository $workflowRepository,
        User $user,
        Request $request,
        WorkflowUserRequestHandler $workflowUserRequestHandler
    ): JsonResponse {
        return $this->setWorkflow(
            $workflowUserRepository,
            $workflowRepository,
            $request,
            $user,
            $workflowUserRequestHandler
        );
    }

    private function setWorkflow(
        WorkflowUserRepository $workflowUserRepository,
        WorkflowRepository $workflowRepository,
        Request $request,
        mixed $entity,
        WorkflowUserRequestHandler $workflowUserRequestHandler
    ): JsonResponse {
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
                (bool) $state,
                $workflowUserRequestHandler
            );
        }

        return new JsonResponse($data);
    }

    private function setWorkflowGroupe(
        WorkflowGroupeRepository $workflowGroupeRepository,
        array $data,
        Groupe $groupe,
        Workflow $workflow,
        bool $state,
        WorkflowGroupeRequestHandler $workflowGroupeRequestHandler
    ): array {
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
            $old = clone $workflowGroupe;
            $workflowGroupe->setState($state);
            $workflowGroupeRequestHandler->handle($old, $workflowGroupe);
        }

        return $data;
    }

    private function setWorkflowUser(
        WorkflowUserRepository $workflowUserRepository,
        array $data,
        ?User $user,
        ?Workflow $workflow,
        bool $state,
        WorkflowUserRequestHandler $workflowUserRequestHandler
    ): array {
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
            $old = clone $workflowUser;
            $workflowUser->setState($state);
            $workflowUserRequestHandler->handle($old, $workflowUser);
        }

        return $data;
    }
}
