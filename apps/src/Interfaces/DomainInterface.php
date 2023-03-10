<?php

namespace Labstag\Interfaces;

use Labstag\Lib\RequestHandlerLib;
use Labstag\Lib\SearchLib;
use Labstag\Lib\ServiceEntityRepositoryLib;

interface DomainInterface
{
    public function getRepository(): ServiceEntityRepositoryLib;

    public function getRequestHandler(): RequestHandlerLib;

    public function getSearchData(): SearchLib;

    public function getSearchForm(): string;

    public function getType(): string;

    public function getUrlAdmin(): array;
}
