<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Entity\Workflow;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Entity\WorkflowUser;
use Labstag\Lib\ApiControllerLib;
use Labstag\RequestHandler\WorkflowGroupeRequestHandler;
use Labstag\RequestHandler\WorkflowUserRequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/guard/workflow")
 */
class GuardWorkflowController extends ApiControllerLib
{
    /**
     * @Route("/group/{group}", name="api_guard_workflowgroup", methods={"POST"})
     */
    public function group(
        Request $request,
        Groupe $group,
        WorkflowGroupeRequestHandler $workflowGroupeRH
    )
    {
        return $this->setWorkflow(
            $request,
            $group,
            WorkflowGroupe::class,
            $workflowGroupeRH
        );
    }

    /**
     * @Route("/groups/{workflow}", name="api_guard_workflowgroups", methods={"POST"})
     */
    public function groups(
        Workflow $workflow,
        WorkflowGroupeRequestHandler $workflowGroupeRH,
        Request $request
    )
    {
        $data    = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state   = $request->request->all('state');
        $groupes = $this->getRepository(Groupe::class)->findAll();
        foreach ($groupes as $group) {
            $data = $this->setWorkflowGroupe(
                $data,
                $group,
                $workflow,
                $state,
                $workflowGroupeRH
            );
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/", name="api_guard_workflow")
     */
    public function index(
        Request $request
    )
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

    /**
     * @Route("/setgroup/{group}/{workflow}", name="api_guard_workflowsetgroup", methods={"POST"})
     */
    public function setgroup(
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
            $data,
            $group,
            $workflow,
            $state,
            $workflowGroupeRH
        );

        return new JsonResponse($data);
    }

    /**
     * @Route("/setuser/{user}/{workflow}", name="api_guard_workflowsetuser", methods={"POST"})
     */
    public function setuser(
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
            $data,
            $this->getRepository(WorkflowUser::class),
            $user,
            $workflow,
            $state,
            $workflowUserRH
        );

        return new JsonResponse($data);
    }

    /**
     * @Route("/user/{user}", name="api_guard_workflowuser", methods={"POST"})
     */
    public function user(
        User $user,
        Request $request,
        WorkflowUserRequestHandler $workflowUserRH
    )
    {
        return $this->setWorkflow(
            $request,
            $user,
            WorkflowUser::class,
            $workflowUserRH
        );
    }

    private function setWorkflow(
        $request,
        $entity,
        $classEntity,
        $requestHandler
    )
    {
        $data      = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        $state     = $request->request->all('state');
        $workflows = $this->getRepository(Workflow::class)->findAll();
        // @var WorkflowUser $route
        foreach ($workflows as $workflow) {
            $data = $this->setWorkflowUser(
                $data,
                $this->getRepository($classEntity),
                $entity,
                $workflow,
                $state,
                $requestHandler
            );
        }

        return new JsonResponse($data);
    }

    private function setWorkflowGroupe(
        $data,
        $group,
        $workflow,
        $state,
        $workflowGroupeRH
    )
    {
        $workflowGroupe = $this->getRepository(WorkflowGroupe::class)->findOneBy(
            [
                'refgroupe'   => $group,
                'refworkflow' => $workflow,
            ]
        );
        if ('0' === $state) {
            if ($workflowGroupe instanceof WorkflowGroupe) {
                $data['delete'] = 1;
                $this->entityManager->remove($workflowGroupe);
                $this->entityManager->flush();
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
        $data,
        $workflowUserRepo,
        $user,
        $workflow,
        $state,
        $workflowUserRH
    )
    {
        $workflowUser = $workflowUserRepo->findOneBy(['refuser' => $user, 'refworkflow' => $workflow]);
        if ('0' === $state) {
            if ($workflowUser instanceof WorkflowUser) {
                $data['delete'] = 1;
                $this->entityManager->remove($workflowUser);
                $this->entityManager->flush();
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
