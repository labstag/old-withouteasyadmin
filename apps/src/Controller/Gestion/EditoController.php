<?php

namespace Labstag\Controller\Gestion;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Edito;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\EditoService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/edito', name: 'admin_edito_')]
class EditoController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Edito $edito
    ): Response
    {
        return $this->setAdmin()->edit($edito);
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

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Edito $edito): Response
    {
        return $this->setAdmin()->preview($edito);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Edito $edito): Response
    {
        return $this->setAdmin()->show($edito);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): EditoService
    {
        $viewService = $this->gestionService->setDomain(Edito::class);
        if (!$viewService instanceof EditoService) {
            throw new Exception('Service must be instance of EditoService');
        }

        return $viewService;
    }
}
