<?php

namespace Labstag\Controller\Gestion\User;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\User;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\GuardService;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/user', name: 'gestion_user_')]
class UserController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        User $user
    ): Response
    {
        return $this->setAdmin()->edit($user);
    }

    #[Route(path: '/{id}/guard', name: 'guard')]
    public function guard(User $user): Response
    {
        $viewService = $this->gestionService->setDomain('guard');
        if (!$viewService instanceof GuardService) {
            throw new Exception('TrashService not found');
        }

        return $viewService->user($user);
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
    public function preview(User $user): Response
    {
        return $this->setAdmin()->preview($user);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->setAdmin()->show($user);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): ViewService
    {
        return $this->gestionService->setDomain(User::class);
    }
}
