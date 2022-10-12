<?php

namespace Labstag\Front;

use Labstag\Entity\Attachment;
use Labstag\Entity\Edito;

class EditoFront extends PageFront
{
    public function setBreadcrumb($content, $breadcrumb)
    {
        if (!$content instanceof Edito) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate('front_edito'),
            'title' => $content->getTitle(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => '']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta($content, $meta)
    {
        if (!$content instanceof Edito) {
            return $meta;
        }

        $meta = $this->getMeta($content->getMetas(), $meta);
        if ($content->getFond() instanceof Attachment) {
            $meta['image'] = $content->getFond()->getName();
        }

        return $meta;
    }
}
