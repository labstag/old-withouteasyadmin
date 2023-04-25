<?php

namespace Labstag\Front;

use Labstag\Entity\Chapter;
use Labstag\Entity\History;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Interfaces\FrontInterface;

class ChapterFront extends HistoryFront implements FrontInterface
{
    public function setBreadcrumb(
        ?EntityFrontInterface $entityFront,
        array $breadcrumb
    ): array
    {
        if (!$entityFront instanceof Chapter) {
            return $breadcrumb;
        }

        /** @var History $history */
        $history      = $entityFront->getHistory();
        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history_chapter',
                [
                    'history' => $history->getSlug(),
                    'chapter' => $entityFront->getSlug(),
                ]
            ),
            'title' => $entityFront->getName(),
        ];

        return $this->setBreadcrumbHistory($history, $breadcrumb);
    }

    public function setMeta(
        ?EntityFrontInterface $entityFront,
        array $meta
    ): array
    {
        if (!$entityFront instanceof Chapter) {
            return $meta;
        }

        /** @var History $history */
        $history     = $entityFront->getHistory();
        $metahistory = $this->getMeta($history->getMetas(), $meta);
        $meta        = $this->getMeta($entityFront->getMetas(), $meta);
        if (isset($metahistory['title'])) {
            $meta['title'] = $meta['title'].' - '.$metahistory['title'];
        }

        return $meta;
    }
}
