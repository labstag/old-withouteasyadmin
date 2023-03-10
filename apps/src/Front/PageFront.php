<?php

namespace Labstag\Front;

use Labstag\Entity\Page;
use Labstag\Interfaces\FrontInterface;
use Labstag\Lib\FrontLib;

class PageFront extends FrontLib
{
    public function setBreadcrumb(
        ?FrontInterface $front,
        array $breadcrumb
    ): array
    {
        if (!$front instanceof Page) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front',
                [
                    'slug' => $front->getSlug(),
                ]
            ),
            'title' => $front->getName(),
        ];
        if ($front->getParent() instanceof Page) {
            $breadcrumb = $this->setBreadcrumbPage($front->getParent(), $breadcrumb);
        }

        return $breadcrumb;
    }

    public function setMeta(
        ?FrontInterface $front,
        array $meta
    ): array
    {
        if (!$front instanceof Page) {
            return $meta;
        }

        return $this->getMeta($front->getMetas(), $meta);
    }

    protected function setBreadcrumbPage(
        ?Page $page,
        array $breadcrumb
    ): array
    {
        if (!$page instanceof Page) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front',
                [
                    'slug' => $page->getSlug(),
                ]
            ),
            'title' => $page->getName(),
        ];
        $parent = $page->getParent();
        if (is_null($parent)) {
            return $breadcrumb;
        }

        if ($parent instanceof Page) {
            $breadcrumb = $this->setBreadcrumbPage($parent, $breadcrumb);
        }

        return $breadcrumb;
    }
}
