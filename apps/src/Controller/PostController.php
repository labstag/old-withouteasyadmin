<?php

namespace Labstag\Controller;

use Labstag\Entity\Post;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\PageRepository;
use Labstag\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: '/mes-articles')]
class PostController extends FrontControllerLib
{
    #[Route(
        path: '/{slug}',
        name: 'front_article',
        priority: 2
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

        if (!$post instanceof Post) {
            return $this->page('mes-articles/'.$slug, $pageRepo);
        }

        return $this->render(
            'front.html.twig',
            ['content' => $post]
        );
    }

    #[Route(
        path: '/categorie/{slug}',
        name: 'front_article_category',
        priority: 2
    )]
    public function category(
        string $slug
    )
    {
        unset($slug);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }

    #[Route(
        path: '/libelle/{code}',
        name: 'front_article_libelle',
        priority: 2
    )]
    public function libelle(
        string $code
    )
    {
        unset($code);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }

    #[Route(
        path: '/user/{username}',
        name: 'front_article_user',
        priority: 2
    )]
    public function user(
        string $username
    )
    {
        unset($username);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }
}
