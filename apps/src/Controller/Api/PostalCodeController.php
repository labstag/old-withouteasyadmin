<?php

namespace Labstag\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("/api")
 */
class PostalCodeController extends AbstractController
{
    /**
     * @Route("/codepostal")
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $query = $request->query->all();

        return $this->json(
            [
                'query' => $query
            ]
        );
    }
}