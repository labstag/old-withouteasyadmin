<?php

namespace Labstag\Interfaces;

use Labstag\Entity\Block;

interface EntityBlockInterface extends EntityInterface
{
    public function getBlock(): ?Block;

    public function setBlock(?Block $block): self;
}
