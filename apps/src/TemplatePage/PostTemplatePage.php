<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Post;
use Labstag\Lib\TemplatePageLib;

class PostTemplatePage extends TemplatePageLib
{
    public function archive(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->postRepository->findPublierArchive($code),
            $this->getRequest()->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/posts/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $this->postRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }

    public function category(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->postRepository->findPublierCategory($code),
            $this->getRequest()->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/posts/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $this->postRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }

    public function libelle(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->postRepository->findPublierLibelle($code),
            $this->getRequest()->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/posts/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $this->postRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }

    public function show(Post $post)
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
                'archives'   => $this->postRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }

    public function user($username)
    {
        $pagination = $this->paginator->paginate(
            $this->postRepository->findPublierUsername($username),
            $this->getRequest()->query->getInt('page', 1),
            10
        );

        return $this->render(
            'front/posts/list.html.twig',
            [
                'pagination' => $pagination,
                'archives'   => $this->postRepository->findDateArchive(),
                'libelles'   => $this->libelleRepository->findByPost(),
                'categories' => $this->categoryRepository->findByPost(),
            ]
        );
    }
}
