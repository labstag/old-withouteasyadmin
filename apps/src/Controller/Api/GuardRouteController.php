<?php

namespace Labstag\Controller\Api;

use Labstag\Entity\User;
use Labstag\Lib\ApiControllerLib;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/guard/route")
 */
class GuardRouteController extends ApiControllerLib
{

    /**
     * @Route("/", name="api_guard_route")
     */
    public function index()
    {

    }

    /**
     * @Route("/group", name="api_guard_routegroup", methods={"POST"})
     */
    public function group()
    {

    }

    /**
     * @Route("/groups", name="api_guard_routegroups", methods={"POST"})
     */
    public function groups()
    {

    }

    /**
     * @Route("/setgroup", name="api_guard_routesetgroup", methods={"POST"})
     */
    public function setgroup()
    {

    }

    /**
     * @Route("/user", name="api_guard_routeuser", methods={"POST"})
     */
    public function user()
    {

    }

    /**
     * @Route("/groups", name="api_guard_routeusers", methods={"POST"})
     */
    public function users()
    {

    }

    /**
     * @Route("/setgroup", name="api_guard_routesetuser", methods={"POST"})
     */
    public function setuser()
    {

    }
}
