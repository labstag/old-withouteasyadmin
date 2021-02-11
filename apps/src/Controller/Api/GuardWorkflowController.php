<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\Groupe;
use Labstag\Entity\User;
use Labstag\Entity\Workflow;
use Labstag\Entity\WorkflowGroupe;
use Labstag\Entity\WorkflowUser;
use Labstag\Lib\ApiControllerLib;
use Labstag\Repository\UserRepository;
use Labstag\Repository\WorkflowGroupeRepository;
use Labstag\Repository\WorkflowRepository;
use Labstag\Repository\WorkflowUserRepository;
use Labstag\RequestHandler\WorkflowGroupeRequestHandler;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/guard/workflow")
 */
class GuardWorkflowController extends ApiControllerLib
{
    /**
     * @Route("/", name="api_guard_workflow")
     */
    public function index(
        WorkflowGroupeRepository $workflowGroupeRepo,
        WorkflowUserRepository $workflowUserRepo,
        UserRepository $userRepository,
        Request $request
    )
    {
        $data = [
            'group' => [],
        ];
        $get  = $request->query->all();
        if (array_key_exists('user', $get)) {
            $data['user'] = [];
            $user         = $userRepository->find($get['user']);
            if (!$user instanceof User) {
                return new JsonResponse($data);
            }

            $results = $workflowUserRepo->findEnable($user);
            foreach ($results as $row) {
                /* @var WorkflowUser $row */
                $data['user'][] = [
                    'entity'     => $row->getRefworkflow()->getEntity(),
                    'transition' => $row->getRefworkflow()->getTransition(),
                ];
            }
        }

        $results = $this->getResultWorkflow($request, $workflowGroupeRepo, $userRepository);
        foreach ($results as $row) {
            /* @var WorkflowGroupe $row */
            $data['group'][] = [
                'groupe'     => $row->getRefgroupe()->getCode(),
                'entity'     => $row->getRefworkflow()->getEntity(),
                'transition' => $row->getRefworkflow()->getTransition(),
            ];
        }

        return new JsonResponse($data);
    }

    private function getResultWorkflow($request, $workflowGroupeRepo, $userRepository)
    {
        $get = $request->query->all();
        if (array_key_exists('user', $get)) {
            $user = $userRepository->find($get['user']);

            return $workflowGroupeRepo->findEnable($user->getGroupe());
        }

        return $workflowGroupeRepo->findEnable();
    }

    /**
     * @Route("/group/{group}", name="api_guard_workflowgroup", methods={"POST"})
     */
    public function group(
        Request $request,
        Groupe $group,
        WorkflowRepository $workflowRepo,
        WorkflowGroupeRepository $workflowGroupeRepo,
        WorkflowGroupeRequestHandler $workflowGroupeRH
    )
    {
        $data = [
            'delete' => 0,
            'add'    => 0,
            'error'  => '',
        ];
        if ('superadmin' == $group->getCode()) {
            return new JsonResponse($data);
        }

        $state = $request->request->get('state');
        if ('0' === $state) {
            $toDelete = $workflowGroupeRepo->findBy(['refgroupe' => $group]);
            foreach ($toDelete as $entity) {
                $data['delete'] = 1;
                $this->entityManager->remove($entity);
            }

            $this->entityManager->flush();

            return new JsonResponse($data);
        }

        $workflows = $workflowRepo->findAll();
        /* @var EntityRoute $route */
        foreach ($workflows as $workflow) {
            $workflowGroupe = $workflowGroupeRepo->findOneBy(['refgroupe' => $group, 'refworkflow' => $workflow]);
            if (!$workflowGroupe instanceof WorkflowGroupe) {
                $workflowGroupe = new WorkflowGroupe();
                $data['add']    = 1;
                $workflowGroupe->setRefgroupe($group);
                $workflowGroupe->setRefworkflow($workflow);
                $old = clone $workflowGroupe;
                $workflowGroupe->setState($state);
                $workflowGroupeRH->handle($old, $workflowGroupe);
            }
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/groups/{workflow}", name="api_guard_workflowgroups", methods={"POST"})
     */
    public function groups(Workflow $workflow)
    {
        unset($workflow);
        $data = [];

        return new JsonResponse($data);
    }

    /**
     * @Route("/setgroup/{group}/{workflow}", name="api_guard_workflowsetgroup", methods={"POST"})
     */
    public function setgroup(Groupe $group, Workflow $workflow)
    {
        unset($group, $workflow);
        $data = [];

        return new JsonResponse($data);
    }

    /**
     * @Route("/user/{user}", name="api_guard_workflowuser", methods={"POST"})
     */
    public function user(User $user)
    {
        unset($user);
        $data = [];

        return new JsonResponse($data);
    }

    /**
     * @Route("/setuser/{user}/{workflow]", name="api_guard_workflowsetuser", methods={"POST"})
     */
    public function setuser(User $user, Workflow $workflow)
    {
        unset($user, $workflow);
        $data = [];

        return new JsonResponse($data);
    }
}
