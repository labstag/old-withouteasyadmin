<?php

namespace Labstag\Front;

use Labstag\Entity\Attachment;
use Labstag\Entity\Edito;
use Labstag\Lib\EntityPublicLib;

class EditoFront extends PageFront
{
    public function setBreadcrumb(
        ?EntityPublicLib $entityPublicLib,
        array $breadcrumb
    ): array
    {
        if (!$entityPublicLib instanceof Edito) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate('front_edito'),
            'title' => $entityPublicLib->getTitle(),
        ];

        $page = $this->pageRepository->findOneBy(
            ['slug' => '']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta(
        ?EntityPublicLib $entityPublicLib,
        array $meta
    ): array
    {
        if (!$entityPublicLib instanceof Edito) {
            return $meta;
        }

        $meta = $this->getMeta($entityPublicLib->getMetas(), $meta);
        if ($entityPublicLib->getFond() instanceof Attachment) {
            $meta['image'] = $entityPublicLib->getFond()->getName();
        }

        return $meta;
    }
}
