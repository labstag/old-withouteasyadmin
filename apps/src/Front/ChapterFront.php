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

        return parent::setBreadcrumb($content->getRefhistory(), $breadcrumb);
    }

    public function setMeta($content, $meta)
    {
        unset($content);

        return $meta;
    }
}
