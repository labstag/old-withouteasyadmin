<?php

namespace Labstag\Controller\Gestion\User;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\AddressUser;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\ViewService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/user/adresse', name: 'gestion_addressuser_')]
class AddressUserController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        AddressUser $addressUser
    ): Response
    {
        return $this->setAdmin()->edit($addressUser);
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
    public function preview(AddressUser $addressUser): Response
    {
        return $this->setAdmin()->preview($addressUser);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(AddressUser $addressUser): Response
    {
        return $this->setAdmin()->show($addressUser);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): ViewService
    {
        return $this->gestionService->setDomain(AddressUser::class);
    }
}
