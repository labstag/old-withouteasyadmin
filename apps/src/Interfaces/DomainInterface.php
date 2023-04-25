<?php

namespace Labstag\Interfaces;

use Labstag\Lib\RepositoryLib;
use Labstag\Lib\SearchLib;

interface DomainInterface
{
    public function getEntity(): string;

    public function getMethodsList(): array;

    public function getRepository(): RepositoryLib;

    public function getSearchData(): SearchLib;

    public function getSearchForm(): string;

    public function getTemplates(): array;

    public function getTitles(): array;

    public function getType(): string;

    public function getUrlAdmin(): array;
}
