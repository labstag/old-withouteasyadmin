<?php

namespace Labstag\Front;

use Labstag\Entity\Chapter;
use Labstag\Lib\EntityPublicLib;

class ChapterFront extends HistoryFront
{
    public function setBreadcrumb(
        ?EntityPublicLib $entityPublicLib,
        array $breadcrumb
    ): array
    {
        if (!$entityPublicLib instanceof Chapter) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history_chapter',
                [
                    'history' => $entityPublicLib->getRefhistory()->getSlug(),
                    'chapter' => $entityPublicLib->getSlug(),
                ]
            ),
            'title' => $entityPublicLib->getName(),
        ];

        return $this->setBreadcrumbHistory($entityPublicLib->getRefhistory(), $breadcrumb);
    }

    public function setMeta(
        ?EntityPublicLib $entityPublicLib,
        array $meta
    ): array
    {
        if (!$entityPublicLib instanceof Chapter) {
            return $meta;
        }

        $history = $this->getMeta($entityPublicLib->getRefhistory()->getMetas(), $meta);
        $meta = $this->getMeta($entityPublicLib->getMetas(), $meta);
        if (isset($history['title'])) {
            $meta['title'] = $meta['title'].' - '.$history['title'];
        }

        return $meta;
    }
}
