<?php

namespace Labstag\Front;

class RouterFront extends PageFront
{
    public function setBreadcrumb($content, $breadcrumb)
    {
        unset($content);

        return $breadcrumb;
    }

    public function setMeta($content, $meta)
    {
        unset($content);

        return $meta;
    }
}
