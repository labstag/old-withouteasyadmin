<?php

namespace Labstag\Front;

use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Interfaces\FrontInterface;

class HistoryFront extends PageFront implements FrontInterface
{
    public function setBreadcrumb(
        ?EntityFrontInterface $entityFront,
        array $breadcrumb
    ): array {
        return $this->setBreadcrumbHistory($entityFront, $breadcrumb);
    }

    public function setBreadcrumbHistory(
        ?EntityFrontInterface $entityFront,
        array $breadcrumb
    ): array {
        if (!$entityFront instanceof History) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history',
                [
                    'slug' => $entityFront->getSlug(),
                ]
            ),
            'title' => $entityFront->getName(),
        ];

        /** @var Page $page */
        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-histoires']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta(
        ?EntityFrontInterface $entityFront,
        array $meta
    ): array {
        if (!$entityFront instanceof History) {
            return $meta;
        }

        return $this->getMeta($entityFront->getMetas(), $meta);
    }
}
