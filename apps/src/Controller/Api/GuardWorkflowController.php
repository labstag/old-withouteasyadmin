<?php

namespace Labstag\Controller\Api;

use Labstag\Lib\ApiControllerLib;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/guard/workflow")
 */
class GuardWorkflowController extends ApiControllerLib
{

    /**
     * @Route("/", name="api_guard_workflow")
     */
    public function index()
    {

    }

    /**
     * @Route("/group/{group}", name="api_guard_workflowgroup", methods={"POST"})
     */
    public function group(Groupe $group)
    {
        unset($group);
    }

    /**
     * @Route("/groups/{entity}/{transition}", name="api_guard_workflowgroups", methods={"POST"})
     */
    public function groups(string $entity, string $transition)
    {
        unset($entity, $transition);
    }

    /**
     * @Route("/setgroup/{group}/{entity}/{transition}", name="api_guard_workflowsetgroup", methods={"POST"})
     */
    public function setgroup(string $group, string $entity, string $transition)
    {
        unset($group, $entity, $transition);
    }

    /**
     * @Route("/user", name="api_guard_workflowuser", methods={"POST"})
     */
    public function user()
    {

    }

    /**
     * @Route("/groups", name="api_guard_workflowusers", methods={"POST"})
     */
    public function users()
    {

    }

    /**
     * @Route("/setgroup", name="api_guard_workflowsetuser", methods={"POST"})
     */
    public function setuser()
    {

    }
}
