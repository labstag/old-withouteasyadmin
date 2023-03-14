<?php

namespace Labstag\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface BlockInterface
{
    public function getEntity(): string;

    public function getForm(): string;

    public function getName(): string;

    public function getType(): string;

    public function isShowForm(): bool;

    public function show(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): ?Response;

    public function template(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): array;
}
