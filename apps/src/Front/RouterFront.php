<?php

namespace Labstag\Front;

use Labstag\Interfaces\EntityFrontInterface;
use Labstag\Interfaces\FrontInterface;

class RouterFront extends PageFront implements FrontInterface
{
    public function setBreadcrumb(
        ?EntityFrontInterface $entityFront,
        array $breadcrumb
    ): array
    {
        unset($entityFront);

        return $breadcrumb;
    }

    public function setMeta(
        ?EntityFrontInterface $entityFront,
        array $meta
    ): array
    {
        unset($entityFront);

        return $meta;
    }
}
