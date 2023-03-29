<?php

namespace Labstag\Controller\Admin\History;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Interfaces\DomainInterface;
use Labstag\Lib\AdminControllerLib;
use Labstag\Repository\ChapterRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/history/chapter', name: 'admin_chapter_')]
class ChapterController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Chapter $chapter
    ): Response
    {
        return $this->form(
            $this->getDomainEntity(),
            is_null($chapter) ? new Chapter() : $chapter,
            'admin/chapter/form.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'trash', methods: ['GET'])]
    #[Route(path: '/', name: 'index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/chapter/index.html.twig',
        );
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
    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'preview', methods: ['GET'])]
    public function showOrPreview(Chapter $chapter): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $chapter,
            'admin/chapter/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainInterface
    {
        $domainLib = $this->domainService->getDomain(Chapter::class);
        if (!$domainLib instanceof DomainInterface) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
