<?php

namespace Labstag\Service;

class FrontService
{
    public function __construct(protected $frontclass)
    {
    }

    public function setBreadcrumb($content)
    {
        $breadcrumb = [];
        foreach ($this->frontclass as $row) {
            $breadcrumb = $row->setBreadcrumb($content, $breadcrumb);
        }

        return array_reverse($breadcrumb);
    }

    public function setMeta($content)
    {
        $meta = [];
        foreach ($this->frontclass as $row) {
            $meta = $row->setMeta($content, $meta);
        }

        return $meta;
    }
}
