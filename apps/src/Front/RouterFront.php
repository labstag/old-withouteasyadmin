<?php

namespace Labstag\Front;

use Labstag\Interfaces\FrontInterface;

class RouterFront extends PageFront
{
    public function setBreadcrumb(
        ?FrontInterface $front,
        array $breadcrumb
    ): array {
        unset($front);

        return $breadcrumb;
    }

    public function setMeta(
        ?FrontInterface $front,
        array $meta
    ): array {
        unset($front);

        return $meta;
    }
}
