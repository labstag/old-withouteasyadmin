<?php

namespace Labstag\Interfaces;

interface PostFormInterface
{
    public function execute(array $success, string $formName): array;

    public function getForm(): string;
}
