<?php

namespace Labstag\Front;

use Labstag\Entity\Page;
use Labstag\Lib\EntityPublicLib;
use Labstag\Lib\FrontLib;

class PageFront extends FrontLib
{
    public function setBreadcrumb(
        ?EntityPublicLib $entityPublicLib,
        array $breadcrumb
    ): array
    {
        if (!$entityPublicLib instanceof Page) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front',
                [
                    'slug' => $entityPublicLib->getSlug(),
                ]
            ),
            'title' => $entityPublicLib->getName(),
        ];
        if ($entityPublicLib->getParent() instanceof Page) {
            $breadcrumb = $this->setBreadcrumbPage($entityPublicLib->getParent(), $breadcrumb);
        }

        return $breadcrumb;
    }

    public function setMeta(
        ?EntityPublicLib $entityPublicLib,
        array $meta
    ): array
    {
        if (!$entityPublicLib instanceof Page) {
            return $meta;
        }

        return $this->getMeta($entityPublicLib->getMetas(), $meta);
    }

    protected function setBreadcrumbPage(
        ?EntityPublicLib $entityPublicLib,
        array $breadcrumb
    ): array
    {
        /** @var Page $entityPublicLib */
        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front',
                [
                    'slug' => $entityPublicLib->getSlug(),
                ]
            ),
            'title' => $entityPublicLib->getName(),
        ];
        $parent = $entityPublicLib->getParent();
        if (is_null($parent)) {
            return $breadcrumb;
        }

        if ($parent instanceof Page) {
            $breadcrumb = $this->setBreadcrumbPage($parent, $breadcrumb);
        }

        return $breadcrumb;
    }
}
