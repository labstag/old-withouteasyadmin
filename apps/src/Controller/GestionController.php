<?php

namespace Labstag\Controller;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Configuration;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\GestionService;
use Labstag\Service\Gestion\Entity\ConfigurationService;
use Labstag\Service\Gestion\Entity\TrashService as EntityTrashService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion')]
class GestionController extends GestionControllerLib
{
    #[Route(path: '/export', name: 'gestion_export')]
    public function export(): RedirectResponse
    {
        $configurationService = $this->paramConfig();

        return $configurationService->export();
    }

    #[Route(path: '/paragraph', name: 'gestion_paragraph', methods: ['GET'])]
    public function iframe(): Response
    {
        return $this->render('gestion/paragraph/iframe.html.twig');
    }

    #[Route(path: '/', name: 'gestion')]
    public function index(): Response
    {
        return $this->gestionConfig()->home();
    }

    #[Route(path: '/oauth', name: 'gestion_oauth')]
    public function oauth(): Response
    {
        return $this->gestionConfig()->oauth();
    }

    #[Route(path: '/param', name: 'gestion_param', methods: ['GET', 'POST'])]
    public function param(): Response
    {
        $configurationService = $this->paramConfig();

        return $configurationService->form();
    }

    #[Route(path: '/themes/{state}', name: 'gestion_themes', defaults: ['state' => 'gestion'])]
    public function themes(string $state): Response
    {
        return $this->gestionConfig()->themes($state);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'gestion_trash')]
    public function trash(): Response
    {
        $viewService = $this->gestionService->setDomain('trash');
        if (!$viewService instanceof EntityTrashService) {
            throw new Exception('TrashService not found');
        }

        return $viewService->list();
    }

    private function gestionConfig(): GestionService
    {
        $viewService = $this->gestionService->setDomain('gestion');
        if (!$viewService instanceof GestionService) {
            throw new Exception('GestionService not found');
        }

        return $viewService;
    }

    private function paramConfig(): ConfigurationService
    {
        $viewService = $this->gestionService->setDomain(Configuration::class);
        if (!$viewService instanceof ConfigurationService) {
            throw new Exception('ConfigurationService not found');
        }

        return $viewService;
    }
}
