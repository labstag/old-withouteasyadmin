<?php

namespace Labstag\Interfaces;

use Doctrine\Common\Collections\Collection;

interface FrontInterface
{
    public function getParagraphs(): Collection;
}
