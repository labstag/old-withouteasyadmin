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

    public function getClassCSS(array $dataClass, EntityBlockInterface $entityBlock): array;
    
    public function context(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): mixed;

    public function twig(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): string;

    public function view(string $twig, array $parameters = []): ?Response;

    public function template(EntityBlockInterface $entityBlock, ?EntityFrontInterface $entityFront): array;
}
