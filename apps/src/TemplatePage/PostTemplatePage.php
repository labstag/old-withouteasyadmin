<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Lib\TemplatePageLib;

class PostTemplatePage extends TemplatePageLib
{
    public function archive(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->postRepository->findPublierArchive($code),
            $this->request->query->getInt('page', 1),
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
            $this->request->query->getInt('page', 1),
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

    public function generateUrl(Page $page, string $route, array $params, bool $relative): string
    {
        $slug = $page->getSlug().'/';
        $url  = match ($route) {
            'user' => $slug.'user/'.$params['username'],
            'show' => $slug.$params['slug'],
            'libelle' => $slug.'libelle/'.$params['code'],
            'category' => $slug.'category/'.$params['code'],
            'archive' => $slug.'/archive/'.$params['code'],
            default => $slug,
        };

        return $this->router->generate(
            'front',
            ['slug' => $url],
            $relative
        );
    }

    public function getId(): string
    {
        return 'post';
    }

    public function launch($matches)
    {
        [
            $case,
            $search,
        ] = $this->getCaseSlug($matches[1]);
        if ('' == $case) {
            throw $this->createNotFoundException();
        }

        switch ($case) {
            case 'user':
                return $this->user($search[1]);
            case 'archive':
                return $this->archive($search[1]);
            case 'category':
                return $this->category($search[1]);
            case 'libelle':
                return $this->libelle($search[1]);
            case 'show':
                $post = $this->postRepository->findOneBy(['slug' => $search[1]]);
                if (!$post instanceof Post) {
                    throw $this->createNotFoundException();
                }
                return $this->show($post);
        }
    }

    public function libelle(string $code)
    {
        $pagination = $this->paginator->paginate(
            $this->postRepository->findPublierLibelle($code),
            $this->request->query->getInt('page', 1),
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
            $this->request->query->getInt('page', 1),
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

    protected function getCaseRegex(): array
    {
        return [
            '/user\/(.*)/'     => 'user',
            '/archive\/(.*)/'  => 'archive',
            '/category\/(.*)/' => 'category',
            '/libelle\/(.*)/'  => 'libelle',
            '/\/(.*)/'         => 'show',
        ];
    }
}
