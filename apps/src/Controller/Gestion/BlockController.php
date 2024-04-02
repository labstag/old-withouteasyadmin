<?php

namespace Labstag\Controller\Gestion;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Block;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\BlockService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/block', name: 'admin_block_')]
class BlockController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Block $block
    ): Response
    {
        return $this->setAdmin()->edit($block);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/move', name: 'move', methods: ['GET', 'POST'])]
    public function move(): Response
    {
        return $this->setAdmin()->move();
    }

    #[Route(path: '/new', name: 'new', methods: ['POST'])]
    public function new(): RedirectResponse
    {
        return $this->setAdmin()->new();
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): BlockService
    {
        $viewService = $this->gestionService->setDomain(Block::class);
        if (!$viewService instanceof BlockService) {
            throw new Exception('Service not found');
        }

        return $viewService;
    }
}
