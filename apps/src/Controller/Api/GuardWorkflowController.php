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
     * @Route("/group", name="api_guard_workflowgroup", methods={"POST"})
     */
    public function group()
    {

    }

    /**
     * @Route("/groups", name="api_guard_workflowgroups", methods={"POST"})
     */
    public function groups()
    {

    }

    /**
     * @Route("/setgroup", name="api_guard_workflowsetgroup", methods={"POST"})
     */
    public function setgroup()
    {

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
