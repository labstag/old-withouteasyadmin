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
    #[Route(path: '/export', name: 'admin_export')]
    public function export(): RedirectResponse
    {
        $configurationService = $this->paramConfig();

        return $configurationService->export();
    }

    #[Route(path: '/paragraph', name: 'admin_paragraph', methods: ['GET'])]
    public function iframe(): Response
    {
        return $this->render('admin/paragraph/iframe.html.twig');
    }

    #[Route(path: '/', name: 'admin')]
    public function index(): Response
    {
        return $this->adminConfig()->home();
    }

    #[Route(path: '/oauth', name: 'admin_oauth')]
    public function oauth(): Response
    {
        return $this->adminConfig()->oauth();
    }

    #[Route(path: '/param', name: 'admin_param', methods: ['GET', 'POST'])]
    public function param(): Response
    {
        $configurationService = $this->paramConfig();

        return $configurationService->form();
    }

    #[Route(path: '/themes/{state}', name: 'admin_themes', defaults: ['state' => 'admin'])]
    public function themes(string $state): Response
    {
        return $this->adminConfig()->themes($state);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_trash')]
    public function trash(): Response
    {
        $viewService = $this->gestionService->setDomain('trash');
        if (!$viewService instanceof EntityTrashService) {
            throw new Exception('TrashService not found');
        }

        return $viewService->list();
    }

    private function adminConfig(): GestionService
    {
        $viewService = $this->gestionService->setDomain('admin');
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
