<?php

namespace Labstag\Interfaces;

interface FrontInterface
{
    public function setBreadcrumb(?EntityFrontInterface $entityFront, array $breadcrumb): array;

    public function setMeta(?EntityFrontInterface $entityFront, array $meta): array;
}
