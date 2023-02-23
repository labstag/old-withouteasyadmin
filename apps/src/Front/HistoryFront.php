<?php

namespace Labstag\Front;

use Labstag\Entity\History;
use Labstag\Lib\EntityPublicLib;

class HistoryFront extends PageFront
{
    public function setBreadcrumb(
        ?EntityPublicLib $entityPublicLib,
        array $breadcrumb
    ): array
    {
        return $this->setBreadcrumbHistory($entityPublicLib, $breadcrumb);
    }

    public function setBreadcrumbHistory(
        ?EntityPublicLib $entityPublicLib,
        array $breadcrumb
    ): array
    {
        if (!$entityPublicLib instanceof History) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history',
                [
                    'slug' => $entityPublicLib->getSlug(),
                ]
            ),
            'title' => $entityPublicLib->getName(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-histoires']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta(
        ?EntityPublicLib $entityPublicLib,
        array $meta
    ): array
    {
        if (!$entityPublicLib instanceof History) {
            return $meta;
        }

        return $this->getMeta($entityPublicLib->getMetas(), $meta);
    }
}
