<?php

namespace Labstag\Front;

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

        return $this->setBreadcrumb($page, $breadcrumb);
    }

    public function setMeta($content, $meta)
    {
        unset($content);

        return $meta;
    }
}
