<?php

namespace Labstag\Controller\Gestion\User;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Groupe;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\GuardService;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/user/groupe', name: 'admin_groupuser_')]
class GroupeController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Groupe $groupe
    ): Response
    {
        return $this->setAdmin()->edit($groupe);
    }

    #[Route(path: '/{id}/guard', name: 'guard')]
    public function guard(Groupe $groupe): Response
    {
        $viewService = $this->gestionService->setDomain('guard');
        if (!$viewService instanceof GuardService) {
            throw new Exception('TrashService not found');
        }

        return $viewService->groupe($groupe);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->setAdmin()->new();
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Groupe $groupe): Response
    {
        return $this->setAdmin()->preview($groupe);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Groupe $groupe): Response
    {
        return $this->setAdmin()->show($groupe);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): ViewService
    {
        return $this->gestionService->setDomain(Groupe::class);
    }
}
