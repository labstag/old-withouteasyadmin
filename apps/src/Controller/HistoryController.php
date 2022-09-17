<?php

namespace Labstag\Controller;

use Symfony\Component\HttpFoundation\Response;
use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\ChapterRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\PageRepository;
use Labstag\Service\HistoryService;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/mes-histoires')]
class HistoryController extends FrontControllerLib
{
    #[Route(
        path: '/{slug}',
        name: 'front_history',
        priority: 2,
        defaults: ['slug' => '']
    )]
    public function bookmark(
        string $slug,
        HistoryRepository $historyRepository,
        PageRepository $pageRepository
    ): Response
    {
        $history = $historyRepository->findOneBy(
            ['slug' => $slug]
        );

        if (!$history instanceof History) {
            if ('' != $slug) {
                throw $this->createNotFoundException();
            }

            return $this->page('mes-histoires', $pageRepository);
        }

        return $this->render(
            'front.html.twig',
            ['content' => $history]
        );
    }

    #[Route(
        path: '/{history}/{chapter}',
        name: 'front_history_chapter',
        priority: 2
    )]
    public function chapter(
        string $history,
        string $chapter,
        ChapterRepository $chapterRepository,
        HistoryRepository $historyRepository
    ): Response
    {
        $history = $historyRepository->findOneBy(
            ['slug' => $history]
        );

        $chapter = $chapterRepository->findOneBy(
            ['slug' => $chapter]
        );

        $test = [
            !$history instanceof History,
            !$chapter instanceof Chapter,
            $chapter->getRefhistory()->getId() != $history->getId(),
        ];

        foreach ($test as $row) {
            if ($row) {
                throw $this->createNotFoundException();
            }
        }

        return $this->render(
            'front.html.twig',
            ['content' => $chapter]
        );
    }

    #[Route(
        path: '/{slug}/pdf',
        name: 'front_history_pdf',
        priority: 3
    )]
    public function pdf(
        string $slug,
        HistoryService $historyService,
        HistoryRepository $historyRepository
    ): RedirectResponse
    {
        $history = $historyRepository->findOneBy(
            ['slug' => $slug]
        );

        if (!$history instanceof History) {
            throw $this->createNotFoundException('Pas de fichier');
        }

        $historyService->process(
            $this->getParameter('file_directory'),
            $history->getId(),
            false
        );

        $filename = $historyService->getFilename();

        $filename = str_replace(
            $this->getParameter('kernel.project_dir').'/public/',
            '/',
            $filename
        );

        return new RedirectResponse($filename, 302);
    }

    #[Route(
        path: '/user/{username}',
        name: 'front_history_user',
        priority: 3
    )]
    public function user(
        string $username
    ): Response
    {
        unset($username);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }
}
