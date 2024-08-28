<?php

namespace Labstag\Controller\Gestion;

use Exception;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\GuardService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/guard')]
class GuardController extends GestionControllerLib
{
    #[Route(path: '/', name: 'gestion_guard_index', methods: ['GET', 'POST'])]
    public function index(): Response
    {
        $viewService = $this->gestionService->setDomain('guard');
        if (!$viewService instanceof GuardService) {
            throw new Exception('TrashService not found');
        }

        return $viewService->global();
    }
}
