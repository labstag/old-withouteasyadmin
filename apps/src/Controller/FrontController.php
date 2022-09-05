<?php

namespace Labstag\Controller;

use Labstag\Entity\Bookmark;
use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\BookmarkRepository;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    #[Route(
        path: '/mes-articles/{slug}',
        name: 'front_article',
        requirements: ['slug' => '.+'],
        defaults: ['slug' => '']
    )]
    public function article(
        string $slug,
        PostRepository $postRepo,
        PageRepository $pageRepo
    )
    {
        $post = $postRepo->findOneBy(
            ['slug' => $slug]
        );

        if ($post instanceof Post) {
            return $this->render(
                'font.html.twig',
                ['content' => $post]
            );
        }

        return $this->front('mes-articles', $pageRepo);
    }

    #[Route(
        path: '/mes-liens/{slug}',
        name: 'front_bookmark',
        requirements: ['slug' => '.+'],
        defaults: ['slug' => '']
    )]
    public function bookmark(
        string $slug,
        BookmarkRepository $bookmarkRepo,
        PageRepository $pageRepo
    )
    {
        $bookmark = $bookmarkRepo->findOneBy(
            ['slug' => $slug]
        );

        if ($bookmark instanceof Bookmark) {
            return new RedirectResponse($bookmark->getUrl(), 302);
        }

        return $this->front('mes-liens', $pageRepo);
    }

    #[Route(path: '/edito', name: 'edito')]
    public function edio(
        EditoRepository $editoRepo
    )
    {
        $edito = $editoRepo->findOnePublier();

        if (!$edito instanceof Edito) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'font.html.twig',
            ['content' => $edito]
        );
    }

    #[Route(path: '/{slug}', name: 'front', requirements: ['slug' => '.+'], defaults: ['slug' => ''], priority: -1)]
    public function front(
        string $slug,
        PageRepository $pageRepo
    )
    {
        $page = $pageRepo->findOneBy(
            ['slug' => $slug]
        );

        if (!$page instanceof Page) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'font.html.twig',
            ['content' => $page]
        );
    }

    #[Route(
        path: '/mes-histoires/{slug}',
        name: 'front_history',
        requirements: ['slug' => '.+'],
        defaults: ['slug' => '']
    )]
    public function history(
        string $slug,
        HistoryRepository $historyRepo,
        PageRepository $pageRepo
    )
    {
        $history = $historyRepo->findOneBy(
            ['slug' => $slug]
        );

        if ($history instanceof History) {
            return $this->render(
                'font.html.twig',
                ['content' => $history]
            );
        }

        return $this->front('mes-histoires', $pageRepo);
    }
}
