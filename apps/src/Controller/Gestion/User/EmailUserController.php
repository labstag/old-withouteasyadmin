<?php

namespace Labstag\Controller\Gestion\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\EmailUser;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/user/email', name: 'gestion_emailuser_')]
class EmailUserController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        EmailUser $emailUser
    ): Response
    {
        return $this->setAdmin()->edit($emailUser);
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
    public function preview(EmailUser $emailUser): Response
    {
        return $this->setAdmin()->preview($emailUser);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(EmailUser $emailUser): Response
    {
        return $this->setAdmin()->show($emailUser);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): ViewService
    {
        return $this->gestionService->setDomain(EmailUser::class);
    }
}
