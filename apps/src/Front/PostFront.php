<?php

namespace Labstag\Front;

use Labstag\Entity\Attachment;
use Labstag\Entity\Category;
use Labstag\Entity\Libelle;
use Labstag\Entity\Post;
use Labstag\Interfaces\FrontInterface;

class PostFront extends PageFront
{
    public function setBreadcrumb(
        ?FrontInterface $front,
        array $breadcrumb
    ): array
    {
        $breadcrumb = $this->setBreadcrumbRouting($breadcrumb);
        if (!$front instanceof Post) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_article',
                [
                    'slug' => $front->getSlug(),
                ]
            ),
            'title' => $front->getTitle(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta(
        ?FrontInterface $front,
        array $meta
    ): array
    {
        if (!$front instanceof Post) {
            return $meta;
        }

        $meta = $this->getMeta($front->getMetas(), $meta);
        if ($front->getImg() instanceof Attachment) {
            $meta['image'] = $front->getImg()->getName();
        }

        return $meta;
    }

    protected function setBreadcrumbRoutingCategory(
        string $route,
        array $params,
        array $breadcrumb
    ): array
    {
        if ('front_article_category' != $route) {
            return $breadcrumb;
        }

        $repository = $this->repositoryService->get(Category::class);
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

    protected function setBreadcrumbRoutingLibelle(
        string $route,
        array $params,
        array $breadcrumb
    ): array
    {
        if ('front_article_libelle' != $route) {
            return $breadcrumb;
        }

        $repository = $this->repositoryService->get(Libelle::class);
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

    protected function setBreadcrumbRoutingYear(
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

    private function setBreadcrumbRouting(array $breadcrumb): array
    {
        $all = $this->request->attributes->all();
        $route = $all['_route'];
        $params = $all['_route_params'];

        $functions = [
            'setBreadcrumbRoutingYear',
            'setBreadcrumbRoutingLibelle',
            'setBreadcrumbRoutingCategory',
        ];
        foreach ($functions as $function) {
            $breadcrumb = call_user_func_array(
                [
                    $this,
                    $function,
                ],
                [
                    $route,
                    $params,
                    $breadcrumb,
                ]
            );
        }

        return $breadcrumb;
    }
}
