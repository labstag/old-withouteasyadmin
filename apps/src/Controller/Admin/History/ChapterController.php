<?php

namespace Labstag\Controller\Admin\History;

use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ChapterRepository;
use Labstag\Service\AdminService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/history/chapter', name: 'admin_chapter_')]
class ChapterController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        Chapter $chapter
    ): Response
    {
        return $this->setAdmin()->edit($chapter);
    }

    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->setAdmin()->index();
    }

    #[Route(path: '/new/{id}', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        History $history,
        ChapterRepository $chapterRepository
    ): RedirectResponse
    {
        $chapter = new Chapter();
        $chapter->setHistory($history);
        $chapter->setName(Uuid::v1());
        $chapter->setPosition((is_countable($history->getChapters()) ? count($history->getChapters()) : 0) + 1);

        $chapterRepository->save($chapter);

        return $this->redirectToRoute('admin_chapter_edit', ['id' => $chapter->getId()]);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function preview(Chapter $chapter): Response
    {
        return $this->setAdmin()->preview($chapter);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Chapter $chapter): Response
    {
        return $this->setAdmin()->show($chapter);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    public function trash(): Response
    {
        return $this->setAdmin()->trash();
    }

    protected function setAdmin(): AdminService
    {
        $this->adminService->setDomain(Chapter::class);

        return $this->adminService;
    }
}
