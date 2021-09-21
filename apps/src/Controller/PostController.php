<?php

namespace Labstag\Controller;

use Labstag\Entity\Post;
use Labstag\Lib\FrontControllerLib;
use Labstag\Repository\CategoryRepository;
use Labstag\Repository\LibelleRepository;
use Labstag\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/post")
 */
class PostController extends FrontControllerLib
{
    /**
     * @Route("/archive/{code}", name="post_archive")
     */
    public function archive(
        PostRepository $postRepository,
        Request $request,
        string $code,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $postRepository->findPublierArchive($code),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/posts/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }

    /**
     * @Route("/category/{code}", name="post_category")
     *
     * @return void
     */
    public function category(
        PostRepository $postRepository,
        Request $request,
        string $code,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $postRepository->findPublierCategory($code),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/posts/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }

    /**
     * @Route("/libelle/{code}", name="post_libelle")
     *
     * @return void
     */
    public function libelle(
        PostRepository $postRepository,
        Request $request,
        string $code,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $posts = $postRepository->findPublierLibelle($code);
        dump($posts->getSql());
        $pagination = $this->paginator->paginate(
            $posts,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/posts/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }

    /**
     * @Route("/{slug}", name="post_show")
     */
    public function show(
        PostRepository $postRepository,
        Post $post,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->setMetaOpenGraph(
            $post->getTitle(),
            $post->getMetaKeywords(),
            $post->getMetaDescription(),
            $post->getImg()
        );

        return $this->render(
            'front/posts/show.html.twig',
            [
                'post'       => $post,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }

    /**
     * @Route("/user/{username}", name="post_user")
     */
    public function user(
        PostRepository $postRepository,
        Request $request,
        $username,
        LibelleRepository $libelleRepository,
        CategoryRepository $categoryRepository
    )
    {
        $pagination = $this->paginator->paginate(
            $postRepository->findPublierUsername($username),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/posts/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $postRepository->findDateArchive(),
                'libelles'   => $libelleRepository->findByPost(),
                'categories' => $categoryRepository->findByPost(),
            ]
        );
    }
}
