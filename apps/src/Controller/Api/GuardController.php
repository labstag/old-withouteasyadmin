<?php
namespace Labstag\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api/guard")
 */
class GuardController extends AbstractController
{

    /**
     * @Route("/user", name="api_guard_user")
     *
     * @return Response
     */
    public function user(Request $request): JsonResponse
    {
        $all = $request->request->all();
        return new JsonResponse($all);

    }

    /**
     * @Route("/group", name="api_guard_group")
     *
     * @return Response
     */
    public function groupe(Request $request): JsonResponse
    {
        $all = $request->request->all();
        return new JsonResponse($all);

    }
}
