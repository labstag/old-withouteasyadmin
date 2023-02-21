<?php

namespace Labstag\Lib;

use Symfony\Contracts\Translation\TranslatorInterface;

abstract class DomainLib
{
    public function __construct(
        protected TranslatorInterface $translator
    )
    {
    }

    public function getSearchForm()
    {
    }

    public function getUrlAdmin()
    {
        return [];
    }
}
