<?php

namespace Labstag\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api")
 */
class CheckPhoneController extends AbstractController
{
    /**
     * @Route("/checkphone", name="api_checkphone")
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $parameters = json_decode($request->getContent(), true);

        return $this->json($parameters);
    }
}
