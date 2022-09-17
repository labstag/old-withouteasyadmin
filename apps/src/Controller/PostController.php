<?php

namespace Labstag\Controller;

use Symfony\Component\HttpFoundation\Response;
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
        priority: 2,
        defaults: ['slug' => '']
    )]
    public function article(
        string $slug,
        PostRepository $postRepository,
        PageRepository $pageRepository
    ): Response
    {
        $post = $postRepository->findOneBy(
            ['slug' => $slug]
        );

        if (!$post instanceof Post) {
            if ('' != $slug) {
                return $this->page('mes-articles/'.$slug, $pageRepository);
            }

            return $this->page('mes-articles', $pageRepository);
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
    ): Response
    {
        unset($slug);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }

    #[Route(
        path: '/libelle/{slug}',
        name: 'front_article_libelle',
        priority: 2
    )]
    public function libelle(
        string $slug
    ): Response
    {
        unset($slug);

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
    ): Response
    {
        unset($username);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }

    #[Route(
        path: '/archive/{year}',
        name: 'front_article_year',
        priority: 2
    )]
    public function year(
        string $year
    ): Response
    {
        unset($year);

        return $this->render(
            'front.html.twig',
            ['content' => null]
        );
    }
}
