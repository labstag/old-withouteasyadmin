<?php

namespace Labstag\TemplatePage;

use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\Page;
use Labstag\Entity\Post;
use Labstag\Lib\TemplatePageLib;

class PostTemplatePage extends TemplatePageLib
{
    public function __invoke($matches)
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
                $post = $this->getRepository(Post::class)->findOneBy(['slug' => $search[1]]);
                if (!$post instanceof Post) {
                    throw $this->createNotFoundException();
                }
                return $this->show($post);
        }
    }

    public function archive(string $code)
    {
        return $this->getList($code, 'findPublierArchive');
    }

    public function category(string $code)
    {
        return $this->getList($code, 'findPublierCategory');
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

    public function libelle(string $code)
    {
        return $this->getList($code, 'findPublierLibelle');
    }

    public function show(Post $post)
    {
        $this->setMetaOpenGraph(
            $post->getTitle(),
            $post->getMetaKeywords(),
            $post->getMetaDescription(),
            $post->getImg()
        );

        return $this->renderData(
            'front/posts/show.html.twig',
            ['post' => $post]
        );
    }

    public function user($username)
    {
        return $this->getList($username, 'findPublierUsername');
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

    private function getList($code, $method)
    {
        $pagination = $this->paginator->paginate(
            $this->getRepository(Post::class)->{$method}($code),
            $this->request->query->getInt('page', 1),
            10
        );

        return $this->renderData(
            'front/posts/list.html.twig',
            ['pagination' => $pagination]
        );
    }

    private function renderData($twig, $data)
    {
        return $this->render(
            $twig,
            array_merge(
                $data,
                [
                    'archives'   => $this->getRepository(Post::class)->findDateArchive(),
                    'libelles'   => $this->getRepository(Libelle::class)->findByPost(),
                    'categories' => $this->getRepository(Category::class)->findByPost(),
                ]
            )
        );
    }
}
