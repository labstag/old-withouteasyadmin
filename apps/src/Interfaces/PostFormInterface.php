<?php

namespace Labstag\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface PostFormInterface
{
    public function context(array $params): mixed;

    public function execute(string $template, array $params): ?Response;

    public function getForm(): string;
}
