<?php

namespace Labstag\Front;

use Labstag\Entity\Page;
use Labstag\Lib\FrontLib;

class PageFront extends FrontLib
{
    public function setBreadcrumb($content, $breadcrumb)
    {
        if (!$content instanceof Page) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName(),
        ];
        if ($content->getParent() instanceof Page) {
            $breadcrumb = $this->setBreadcrumbPage($content->getParent(), $breadcrumb);
        }

        return $breadcrumb;
    }

    public function setMeta($content, $meta)
    {
        unset($content);

        return $meta;
    }

    private function setBreadcrumbPage($content, $breadcrumb)
    {
        $breadcrumb[] = [
            'route' => $this->router->generate(
                'front',
                [
                    'slug' => $content->getSlug(),
                ]
            ),
            'title' => $content->getName(),
        ];
        if ($content->getParent() instanceof Page) {
            $breadcrumb = $this->setBreadcrumbPage($content->getParent(), $breadcrumb);
        }

        return $breadcrumb;
    }
}
