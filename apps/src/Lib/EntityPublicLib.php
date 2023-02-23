<?php

namespace Labstag\Lib;

use Doctrine\Common\Collections\Collection;

interface EntityPublicLib
{
    public function getParagraphs(): Collection;
}