<?php

namespace Labstag\Front;

use Labstag\Entity\History;

class HistoryFront extends PageFront
{
    public function setBreadcrumb($content, $breadcrumb)
    {
        if (!$content instanceof History) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-histoires']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta($content, $meta)
    {
        unset($content);

        return $meta;
    }
}
