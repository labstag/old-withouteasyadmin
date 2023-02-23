<?php

namespace Labstag\Front;

use Labstag\Entity\Attachment;
use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\Post;
use Labstag\Lib\EntityPublicLib;

class PostFront extends PageFront
{
    public function setBreadcrumb(
        ?EntityPublicLib $entityPublicLib,
        array $breadcrumb
    ): array
    {
        $breadcrumb = $this->setBreadcrumbRouting($breadcrumb);
        if (!$entityPublicLib instanceof Post) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_article',
                [
                    'slug' => $entityPublicLib->getSlug(),
                ]
            ),
            'title' => $entityPublicLib->getTitle(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta(
        ?EntityPublicLib $entityPublicLib,
        array $meta
    ): array
    {
        if (!$entityPublicLib instanceof Post) {
            return $meta;
        }

        $meta = $this->getMeta($entityPublicLib->getMetas(), $meta);
        if ($entityPublicLib->getImg() instanceof Attachment) {
            $meta['image'] = $entityPublicLib->getImg()->getName();
        }

        return $meta;
    }

    private function setBreadcrumbRouting(array $breadcrumb): array
    {
        $all = $this->request->attributes->all();
        $route = $all['_route'];
        $params = $all['_route_params'];
        $breadcrumb = $this->setBreadcrumbRoutingYear($route, $params, $breadcrumb);
        $breadcrumb = $this->setBreadcrumbRoutingLibelle($route, $params, $breadcrumb);

        return $this->setBreadcrumbRoutingCategory($route, $params, $breadcrumb);
    }

    private function setBreadcrumbRoutingCategory(
        string $route,
        array $params,
        array $breadcrumb
    ): array
    {
        if ('front_article_category' != $route) {
            return $breadcrumb;
        }

        $repository = $this->getRepository(Category::class);
        $category = $repository->findOneBy(
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

    private function setBreadcrumbRoutingLibelle(
        string $route,
        array $params,
        array $breadcrumb
    ): array
    {
        if ('front_article_libelle' != $route) {
            return $breadcrumb;
        }

        $repository = $this->getRepository(Libelle::class);
        $libelle = $repository->findOneBy(
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

    private function setBreadcrumbRoutingYear(
        string $route,
        array $params,
        array $breadcrumb
    ): array
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
        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles/archive']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }
}
