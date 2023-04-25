<?php

namespace Labstag\Front;

use Labstag\Entity\Page;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Interfaces\FrontInterface;
use Labstag\Lib\FrontLib;

class PageFront extends FrontLib implements FrontInterface
{
    public function setBreadcrumb(
        ?EntityFrontInterface $entityFront,
        array $breadcrumb
    ): array
    {
        if (!$entityFront instanceof Page) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front',
                [
                    'slug' => $entityFront->getSlug(),
                ]
            ),
            'title' => $entityFront->getName(),
        ];
        if ($entityFront->getParent() instanceof Page) {
            $breadcrumb = $this->setBreadcrumbPage($entityFront->getParent(), $breadcrumb);
        }

        return $breadcrumb;
    }

    public function setMeta(
        ?EntityFrontInterface $entityFront,
        array $meta
    ): array
    {
        if (!$entityFront instanceof Page) {
            return $meta;
        }

        return $this->getMeta($entityFront->getMetas(), $meta);
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
