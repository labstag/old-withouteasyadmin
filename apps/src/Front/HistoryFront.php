<?php

namespace Labstag\Front;

use Labstag\Entity\History;
use Labstag\Entity\Page;
use Labstag\Interfaces\FrontInterface;

class HistoryFront extends PageFront
{
    public function setBreadcrumb(
        ?FrontInterface $front,
        array $breadcrumb
    ): array {
        return $this->setBreadcrumbHistory($front, $breadcrumb);
    }

    public function setBreadcrumbHistory(
        ?FrontInterface $front,
        array $breadcrumb
    ): array {
        if (!$front instanceof History) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history',
                [
                    'slug' => $front->getSlug(),
                ]
            ),
            'title' => $front->getName(),
        ];

        /** @var Page $page */
        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-histoires']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta(
        ?FrontInterface $front,
        array $meta
    ): array {
        if (!$front instanceof History) {
            return $meta;
        }

        return $this->getMeta($front->getMetas(), $meta);
    }
}
