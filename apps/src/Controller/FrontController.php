<?php

namespace Labstag\Controller;

use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Lib\ControllerLib;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends ControllerLib
{
    #[Route(path: '/article/{slug}', name: 'front_article', requirements: ['slug' => '.+'], defaults: ['slug' => ''])]
    public function article(
        string $slug,
        PostRepository $postRepo
    ): mixed
    {
        $post = $postRepo->findOneBy(
            ['frontslug' => $slug]
        );

        if (!$post instanceof Post) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'front/post/index.html.twig',
            ['content' => $post]
        );
    }

    #[Route(path: '/{slug}', name: 'front', requirements: ['slug' => '.+'], defaults: ['slug' => ''], priority: -1)]
    public function front(
        string $slug,
        PageRepository $pageRepo
    ): mixed
    {
        $page = $pageRepo->findOneBy(
            ['frontslug' => $slug]
        );

        if (!$page instanceof Page) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'front/index.html.twig',
            ['content' => $page]
        );
    }

    #[Route(path: '/histoire/{slug}', name: 'front_history', requirements: ['slug' => '.+'], defaults: ['slug' => ''])]
    public function history(
        string $slug,
        HistoryRepository $historyRepo
    ): mixed
    {
        $history = $historyRepo->findOneBy(
            ['frontslug' => $slug]
        );

        if (!$history instanceof History) {
            throw $this->createNotFoundException();
        }

        return $this->render(
            'front/history/index.html.twig',
            ['content' => $history]
        );
    }
}
