<?php

namespace Labstag\Front;

use Labstag\Entity\Post;

class PostFront extends PageFront
{
    public function setBreadcrumb($content, $breadcrumb)
    {
        if (!$content instanceof Post) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front_article',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getTitle(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => 'mes-articles']
        );

        return parent::setBreadcrumb($page, $breadcrumb);
    }

    public function setMeta($content, $meta)
    {
        unset($content);

        return $meta;
    }
}
