<?php

namespace Labstag\Controller\Admin\History;

use Exception;
use Labstag\Annotation\IgnoreSoftDelete;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Lib\AdminControllerLib;
use Labstag\Lib\DomainLib;
use Labstag\Repository\ChapterRepository;
use Labstag\RequestHandler\ChapterRequestHandler;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

#[Route(path: '/admin/history/chapter')]
class ChapterController extends AdminControllerLib
{
    #[Route(path: '/{id}/edit', name: 'admin_chapter_edit', methods: ['GET', 'POST'])]
    public function edit(
        ?Chapter $chapter
    ): Response {
        return $this->form(
            $this->getDomainEntity(),
            is_null($chapter) ? new Chapter() : $chapter,
            'admin/chapter/form.html.twig'
        );
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/trash', name: 'admin_chapter_trash', methods: ['GET'])]
    #[Route(path: '/', name: 'admin_chapter_index', methods: ['GET'])]
    public function indexOrTrash(): Response
    {
        return $this->listOrTrash(
            $this->getDomainEntity(),
            'admin/chapter/index.html.twig',
        );
    }

    #[Route(path: '/new/{id}', name: 'admin_chapter_new', methods: ['GET', 'POST'])]
    public function new(
        History $history,
        ChapterRepository $chapterRepository,
        ChapterRequestHandler $chapterRequestHandler
    ): RedirectResponse {
        $chapter = new Chapter();
        $chapter->setRefhistory($history);
        $chapter->setName(Uuid::v1());
        $chapter->setPosition((is_countable($history->getChapters()) ? count($history->getChapters()) : 0) + 1);

        $old = clone $chapter;
        $chapterRepository->add($chapter);
        $chapterRequestHandler->handle($old, $chapter);

        return $this->redirectToRoute('admin_chapter_edit', ['id' => $chapter->getId()]);
    }

    #[IgnoreSoftDelete]
    #[Route(path: '/{id}', name: 'admin_chapter_show', methods: ['GET'])]
    #[Route(path: '/preview/{id}', name: 'admin_chapter_preview', methods: ['GET'])]
    public function showOrPreview(Chapter $chapter): Response
    {
        return $this->renderShowOrPreview(
            $this->getDomainEntity(),
            $chapter,
            'admin/chapter/show.html.twig'
        );
    }

    protected function getDomainEntity(): DomainLib
    {
        $domainLib = $this->domainService->getDomain(Chapter::class);
        if (!$domainLib instanceof DomainLib) {
            throw new Exception('Domain not found');
        }

        return $domainLib;
    }
}
