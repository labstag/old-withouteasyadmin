<?php

namespace Labstag\Front;

use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\Post;

class PostFront extends PageFront
{
    public function setBreadcrumb($content, $breadcrumb)
    {
        $breadcrumb = $this->setBreadcrumbRouting($breadcrumb);
        if (!$content instanceof Post) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_article',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getTitle(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta($content, $meta)
    {
        if (!$content instanceof Post) {
            return $meta;
        }

        return $this->getMeta($content->getMetas(), $meta);
    }

    private function setBreadcrumbRouting($breadcrumb)
    {
        $all        = $this->request->attributes->all();
        $route      = $all['_route'];
        $params     = $all['_route_params'];
        $breadcrumb = $this->setBreadcrumbRoutingYear($route, $params, $breadcrumb);
        $breadcrumb = $this->setBreadcrumbRoutingLibelle($route, $params, $breadcrumb);

        return $this->setBreadcrumbRoutingCategory($route, $params, $breadcrumb);
    }

    private function setBreadcrumbRoutingCategory($route, $params, $breadcrumb)
    {
        if ('front_article_category' != $route) {
            return $breadcrumb;
        }

        $repository = $this->getRepository(Category::class);
        $category   = $repository->findOneBy(
            [
                'slug' => $params['slug'],
            ]
        );

        $breadcrumb[] = [
            'route' => $this->router->generate(
                $route,
                $params
            ),
            'title' => $category->getName(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    private function setBreadcrumbRoutingLibelle($route, $params, $breadcrumb)
    {
        if ('front_article_libelle' != $route) {
            return $breadcrumb;
        }

        $repository = $this->getRepository(Libelle::class);
        $libelle    = $repository->findOneBy(
            [
                'slug' => $params['slug'],
            ]
        );

        $breadcrumb[] = [
            'route' => $this->router->generate(
                $route,
                $params
            ),
            'title' => $libelle->getName(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    private function setBreadcrumbRoutingYear($route, $params, $breadcrumb)
    {
        if ('front_article_year' != $route && !isset($params['year'])) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                $route,
                $params
            ),
            'title' => $params['year'],
        ];
        $page         = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles/archive']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }
}
