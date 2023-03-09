<?php

namespace Labstag\Front;

use Labstag\Entity\Chapter;
use Labstag\Interfaces\FrontInterface;

class ChapterFront extends HistoryFront
{
    public function setBreadcrumb(
        ?FrontInterface $front,
        array $breadcrumb
    ): array
    {
        if (!$front instanceof Chapter) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history_chapter',
                [
                    'history' => $front->getRefhistory()->getSlug(),
                    'chapter' => $front->getSlug(),
                ]
            ),
            'title' => $front->getName(),
        ];

        return $this->setBreadcrumbHistory($front->getRefhistory(), $breadcrumb);
    }

    public function setMeta(
        ?FrontInterface $front,
        array $meta
    ): array
    {
        if (!$front instanceof Chapter) {
            return $meta;
        }

        $history = $this->getMeta($front->getRefhistory()->getMetas(), $meta);
        $meta    = $this->getMeta($front->getMetas(), $meta);
        if (isset($history['title'])) {
            $meta['title'] = $meta['title'].' - '.$history['title'];
        }

        return $meta;
    }
}
