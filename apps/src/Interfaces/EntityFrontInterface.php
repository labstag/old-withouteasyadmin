<?php

namespace Labstag\Interfaces;

use Doctrine\Common\Collections\Collection;

interface EntityFrontInterface extends EntityInterface
{
    public function getParagraphs(): Collection;
}
