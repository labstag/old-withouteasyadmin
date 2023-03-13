<?php

namespace Labstag\Interfaces;

use Doctrine\Common\Collections\Collection;

interface FrontInterface extends EntityInterface
{
    public function getParagraphs(): Collection;
}
