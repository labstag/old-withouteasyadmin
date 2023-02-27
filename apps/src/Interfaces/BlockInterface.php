<?php

namespace Labstag\Interfaces;

use Labstag\Entity\Block;

interface BlockInterface
{
    public function getBlock(): ?Block;
}
