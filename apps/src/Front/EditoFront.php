<?php

namespace Labstag\Front;

use Labstag\Entity\Attachment;
use Labstag\Entity\Edito;
use Labstag\Entity\Page;
use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Interfaces\FrontInterface;

class EditoFront extends PageFront implements FrontInterface
{
    public function setBreadcrumb(
        ?EntityFrontInterface $entityFront,
        array $breadcrumb
    ): array
    {
        if (!$entityFront instanceof Edito) {
            return $breadcrumb;
        }

        $breadcrumb[] = [
            'route' => $this->router->generate('front_edito'),
            'title' => $entityFront->getTitle(),
        ];

        /** @var Page $page */
        $page = $this->pageRepository->findOneBy(
            ['slug' => '']
        );

        return $this->setBreadcrumbPage($page, $breadcrumb);
    }

    public function setMeta(
        ?EntityFrontInterface $entityFront,
        array $meta
    ): array
    {
        if (!$entityFront instanceof Edito) {
            return $meta;
        }

        $meta = $this->getMeta($entityFront->getMetas(), $meta);
        if ($entityFront->getFond() instanceof Attachment) {
            $meta['image'] = $entityFront->getFond()->getName();
        }

        return $meta;
    }
}
