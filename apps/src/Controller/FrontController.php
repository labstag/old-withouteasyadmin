<?php

namespace Labstag\Controller;

use Labstag\Entity\Edito;
use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\EditoRepository;
use Labstag\Repository\HistoryRepository;
use Labstag\Repository\PageRepository;
use Labstag\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends FrontControllerLib
{
    #[Route(path: '/article/{slug}', name: 'front_article', requirements: ['slug' => '.+'], defaults: ['slug' => ''])]
    public function article(
        string $slug,
        PostRepository $postRepo
    )
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
            'front/edito.html.twig',
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
    )
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
