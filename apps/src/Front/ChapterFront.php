<?php

namespace Labstag\Front;

use Labstag\Entity\Chapter;

class ChapterFront extends HistoryFront
{
    public function setBreadcrumb($content, $breadcrumb)
    {
        if (!$content instanceof Chapter) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_history_chapter',
                [
                    'history' => $content->getRefhistory()->getSlug(),
                    'chapter' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName(),
        ];

        return $this->setBreadcrumbHistory($content->getRefhistory(), $breadcrumb);
    }

    public function setMeta($content, $meta)
    {
        if (!$content instanceof Chapter) {
            return $meta;
        }

        $history = $this->getMeta($content->getRefhistory()->getMetas(), $meta);
        $chapter = $this->getMeta($content->getMetas(), $meta);
        if (isset($history['title'])) {
            $chapter['title'] = $chapter['title'].' - '.$history['title'];
        }

        return $chapter;
    }
}
