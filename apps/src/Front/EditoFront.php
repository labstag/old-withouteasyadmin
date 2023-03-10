<?php

namespace Labstag\Front;

use Labstag\Entity\Attachment;
use Labstag\Entity\Edito;
use Labstag\Entity\Page;
use Labstag\Interfaces\FrontInterface;

class EditoFront extends PageFront
{
    public function setBreadcrumb(
        ?FrontInterface $front,
        array $breadcrumb
    ): array
    {
        if (!$front instanceof Edito) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate('front_edito'),
            'title' => $front->getTitle(),
        ];

        /** @var Page $page */
        $page = $this->pageRepository->findOneBy(
            ['slug' => '']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta(
        ?FrontInterface $front,
        array $meta
    ): array
    {
        if (!$front instanceof Edito) {
            return $meta;
        }

        $meta = $this->getMeta($front->getMetas(), $meta);
        if ($front->getFond() instanceof Attachment) {
            $meta['image'] = $front->getFond()->getName();
        }

        return $meta;
    }
}
