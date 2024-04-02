<?php

namespace Labstag\Controller\Gestion;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Bookmark;
use Labstag\Lib\GestionControllerLib;
use Labstag\Queue\EnqueueMethod;
use Labstag\Service\Gestion\Entity\BookmarkService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/gestion/bookmark', name: 'gestion_bookmark_')]
class BookmarkController extends GestionControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Bookmark $bookmark
    ): Response
    {
        return $this->setAdmin()->edit($bookmark);
    }

    #[Route(path: '/import', name: 'import', methods: ['GET', 'POST'])]
    public function import(Security $security, EnqueueMethod $enqueueMethod): Response
    {
        return $this->setAdmin()->import($security, $enqueueMethod);
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
    public function preview(Bookmark $bookmark): Response
    {
        return $this->setAdmin()->preview($bookmark);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Bookmark $bookmark): Response
    {
        return $this->setAdmin()->show($bookmark);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): BookmarkService
    {
        $viewService = $this->gestionService->setDomain(Bookmark::class);
        if (!$viewService instanceof BookmarkService) {
            throw new Exception('Service must be an instance of BookmarkService');
        }

        return $viewService;
    }
}
