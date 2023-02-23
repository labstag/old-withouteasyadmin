<?php

namespace Labstag\Front;

use Labstag\Lib\EntityPublicLib;

class RouterFront extends PageFront
{
    public function setBreadcrumb(
        ?EntityPublicLib $entityPublicLib,
        array $breadcrumb
    ): array
    {
        unset($entityPublicLib);

        return $breadcrumb;
    }

    public function setMeta(
        ?EntityPublicLib $entityPublicLib,
        array $meta
    ): array
    {
        unset($entityPublicLib);

        return $meta;
    }
}
