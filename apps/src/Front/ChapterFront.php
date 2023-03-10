<?php

namespace Labstag\Front;

use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Interfaces\FrontInterface;

class ChapterFront extends HistoryFront
{
    public function setBreadcrumb(
        ?FrontInterface $front,
        array $breadcrumb
    ): array {
        if (!$front instanceof Chapter) {
            return $breadcrumb;
        }

        /** @var History $history */
        $history      = $front->getRefhistory();
        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history_chapter',
                [
                    'history' => $history->getSlug(),
                    'chapter' => $front->getSlug(),
                ]
            ),
            'title' => $front->getName(),
        ];

        return $this->setBreadcrumbHistory($history, $breadcrumb);
    }

    public function setMeta(
        ?FrontInterface $front,
        array $meta
    ): array {
        if (!$front instanceof Chapter) {
            return $meta;
        }

        /** @var History $history */
        $history     = $front->getRefhistory();
        $metahistory = $this->getMeta($history->getMetas(), $meta);
        $meta        = $this->getMeta($front->getMetas(), $meta);
        if (isset($metahistory['title'])) {
            $meta['title'] = $meta['title'].' - '.$metahistory['title'];
        }

        return $meta;
    }
}
