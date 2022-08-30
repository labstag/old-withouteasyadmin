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
        WorkflowUserRepository $workflowUserRepo,
        WorkflowRepository $workflowRepo,
        Request $request,
        Groupe $group,
        WorkflowGroupeRequestHandler $workflowGroupeRH
    )
    {
        return $this->setWorkflow(
            $workflowUserRepo,
            $workflowRepo,
            $request,
            $group,
            $workflowGroupeRH
        );
    }

    #[Route(path: '/groups/{workflow}', name: 'api_guard_workflowgroups', methods: ['POST'])]
    public function groups(
        WorkflowGroupeRepository $WorkflowGroupeRepo,
        Workflow $workflow,
        WorkflowGroupeRequestHandler $workflowGroupeRH,
        Request $request,
        GroupeRepository $groupeRepo
    )
    {
        $data    = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state   = $request->request->all('state');
        $groupes = $groupeRepo->findAll();
        foreach ($groupes as $group) {
            $data = $this->setWorkflowGroupe(
                $WorkflowGroupeRepo,
                $data,
                $group,
                $workflow,
                $state,
                $workflowGroupeRH
            );
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/', name: 'api_guard_workflow')]
    public function index(Request $request)
    {
        $data    = [
            'group' => [],
        ];
        $get     = $request->query->all();
        $data    = $this->getGuardRouteOrWorkflow($data, $get, WorkflowUser::class);
        $results = $this->getResultWorkflow($request, WorkflowGroupe::class);
        foreach ($results as $row) {
            // @var WorkflowGroupe $row
            $data['group'][] = [
                'groupe'     => $row->getRefgroupe()->getCode(),
                'entity'     => $row->getRefworkflow()->getEntity(),
                'transition' => $row->getRefworkflow()->getTransition(),
            ];
        }

        return new JsonResponse($data);
    }

    #[Route(path: '/setgroup/{group}/{workflow}', name: 'api_guard_workflowsetgroup', methods: ['POST'])]
    public function setgroup(
        WorkflowGroupeRepository $repository,
        Groupe $group,
        Workflow $workflow,
        Request $request,
        WorkflowGroupeRequestHandler $workflowGroupeRH
    )
    {
        $data  = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state = $request->request->all('state');
        $data  = $this->setWorkflowGroupe(
            $repository,
            $data,
            $group,
            $workflow,
            $state,
            $workflowGroupeRH
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/setuser/{user}/{workflow}', name: 'api_guard_workflowsetuser', methods: ['POST'])]
    public function setuser(
        WorkflowUserRepository $repository,
        User $user,
        Workflow $workflow,
        Request $request,
        WorkflowUserRequestHandler $workflowUserRH
    )
    {
        $data  = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state = $request->request->all('state');
        $data  = $this->setWorkflowUser(
            $repository,
            $data,
            $user,
            $workflow,
            $state,
            $workflowUserRH
        );

        return new JsonResponse($data);
    }

    #[Route(path: '/user/{user}', name: 'api_guard_workflowuser', methods: ['POST'])]
    public function user(
        WorkflowUserRepository $workflowUserRepo,
        WorkflowRepository $workflowRepo,
        User $user,
        Request $request,
        WorkflowUserRequestHandler $workflowUserRH
    )
    {
        return $this->setWorkflow(
            $workflowUserRepo,
            $workflowRepo,
            $request,
            $user,
            $workflowUserRH
        );
    }

    private function setWorkflow(
        WorkflowUserRepository $workflowUserRepo,
        WorkflowRepository $workflowRepo,
        $request,
        $entity,
        $requestHandler
    )
    {
        $data      = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state     = $request->request->all('state');
        $workflows = $workflowRepo->findAll();
        // @var WorkflowUser $route
        foreach ($workflows as $workflow) {
            $data = $this->setWorkflowUser(
                $workflowUserRepo,
                $data,
                $entity,
                $workflow,
                $state,
                $requestHandler
            );
        }

        return new JsonResponse($data);
    }

    private function setWorkflowGroupe(
        WorkflowGroupeRepository $repository,
        $data,
        $group,
        $workflow,
        $state,
        $workflowGroupeRH
    )
    {
        $workflowGroupe = $repository->findOneBy(
            [
                'refgroupe'   => $group,
                'refworkflow' => $workflow,
            ]
        );
        if ('0' === $state) {
            if ($workflowGroupe instanceof WorkflowGroupe) {
                $data['delete'] = 1;
                $repository->remove($workflowGroupe);
            }

            return $data;
        }

        if ('superadmin' === $group->getCode()) {
            return $data;
        }

        if (!$workflowGroupe instanceof WorkflowGroupe) {
            $workflowGroupe = new WorkflowGroupe();
            $data['add']    = 1;
            $workflowGroupe->setRefgroupe($group);
            $workflowGroupe->setRefworkflow($workflow);
            $old = clone $workflowGroupe;
            $workflowGroupe->setState($state);
            $workflowGroupeRH->handle($old, $workflowGroupe);
        }

        return $data;
    }

    private function setWorkflowUser(
        WorkflowUserRepository $repository,
        $data,
        $user,
        $workflow,
        $state,
        $workflowUserRH
    )
    {
        $workflowUser = $repository->findOneBy(['refuser' => $user, 'refworkflow' => $workflow]);
        if ('0' === $state) {
            if ($workflowUser instanceof WorkflowUser) {
                $data['delete'] = 1;
                $repository->remove($workflowUser);
            }

            return $data;
        }

        if ('superadmin' === $user->getRefgroupe()->getCode()) {
            return $data;
        }

        if (!$workflowUser instanceof WorkflowUser) {
            $data['add']  = 1;
            $workflowUser = new WorkflowUser();
            $workflowUser->setRefuser($user);
            $workflowUser->setRefworkflow($workflow);
            $old = clone $workflowUser;
            $workflowUser->setState($state);
            $workflowUserRH->handle($old, $workflowUser);
        }

        return $data;
    }
}
