<?php

namespace Labstag\Controller\Gestion;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Memo;
use Labstag\Lib\GestionControllerLib;
use Labstag\Service\Gestion\Entity\MemoService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/memo', name: 'admin_memo_')]
class MemoController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Memo $memo
    ): Response
    {
        return $this->setAdmin()->edit($memo);
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
    public function preview(Memo $memo): Response
    {
        return $this->setAdmin()->preview($memo);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Memo $memo): Response
    {
        return $this->setAdmin()->show($memo);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): MemoService
    {
        $viewService = $this->gestionService->setDomain(Memo::class);
        if (!$viewService instanceof MemoService) {
            throw new Exception('Service not found');
        }

        return $viewService;
    }
}
