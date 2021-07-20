<?php

namespace Labstag\Controller;

use Labstag\Entity\Post;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends FrontControllerLib
{
    /**
     * @Route("/archive/{code}", name="post_archive")
     */
    public function archive(PostRepository $postRepository, string $code)
    {
        return $this->render(
            'front/posts/list.html.twig',
            [
                'posts'    => $postRepository->findPublierArchive($code),
                'archives' => $postRepository->findDateArchive(),
            ]
        );
    }

    /**
     * @Route("/{slug}", name="post_show")
     */
    public function show(PostRepository $postRepository, Post $post)
    {
        return $this->render(
            'front/posts/show.html.twig',
            [
                'post'     => $post,
                'archives' => $postRepository->findDateArchive(),
            ]
        );
    }

    /**
     * @Route("/user/{username}", name="post_user")
     */
    public function user(PostRepository $postRepository, $username)
    {
        return $this->render(
            'front/posts/list.html.twig',
            [
                'posts'    => $postRepository->findPublierUsername($username),
                'archives' => $postRepository->findDateArchive(),
            ]
        );
    }
}
