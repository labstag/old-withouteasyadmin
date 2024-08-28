<?php

namespace Labstag\Controller\Gestion;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Layout;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\LayoutService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/layout', name: 'gestion_layout_')]
class LayoutController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Layout $layout
    ): Response
    {
        return $this->setAdmin()->edit($layout);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->trash();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(): RedirectResponse
    {
        return $this->setAdmin()->new();
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Layout $layout): Response
    {
        return $this->setAdmin()->preview($layout);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Layout $layout): Response
    {
        return $this->setAdmin()->show($layout);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): LayoutService
    {
        $viewService = $this->gestionService->setDomain(Layout::class);
        if (!$viewService instanceof LayoutService) {
            throw new Exception('Service not found');
        }

        return $viewService;
    }
}
