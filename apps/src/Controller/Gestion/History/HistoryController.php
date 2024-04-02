<?php

namespace Labstag\Controller\Gestion\History;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\History;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\HistoryService as EntityHistoryService;
use Labstag\Service\HistoryService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/history', name: 'admin_history_')]
class HistoryController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        History $history
    ): Response
    {
        return $this->setAdmin()->edit($history);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Security $security
    ): RedirectResponse
    {
        return $this->setAdmin()->add($security);
    }

    #[Route(path: '/{id}/pdf', name: 'pdf', methods: ['GET'])]
    public function pdf(HistoryService $historyService, History $history): RedirectResponse
    {
        return $this->setAdmin()->pdf($historyService, $history);
    }

    #[Route(path: '/{id}/move', name: 'move', methods: ['GET', 'POST'])]
    public function position(History $history): Response
    {
        return $this->setAdmin()->position($history);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(History $history): Response
    {
        return $this->setAdmin()->preview($history);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(History $history): Response
    {
        return $this->setAdmin()->show($history);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): EntityHistoryService
    {
        $viewService = $this->gestionService->setDomain(History::class);
        if (!$viewService instanceof EntityHistoryService) {
            throw new Exception('Service must be instance of '.EntityHistoryService::class);
        }

        return $viewService;
    }
}
