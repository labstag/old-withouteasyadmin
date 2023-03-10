<?php

namespace Labstag\Lib;

use Labstag\Interfaces\DomainInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class DomainLib implements DomainInterface
{
    public function __construct(
        protected TranslatorInterface $translator
    )
    {
    }

    public function getSearchForm(): string
    {
        return '';
    }

    public function getUrlAdmin(): array
    {
        return [];
    }
}
