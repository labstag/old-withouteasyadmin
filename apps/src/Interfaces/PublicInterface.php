<?php

namespace Labstag\Interfaces;

use Doctrine\Common\Collections\Collection;

interface PublicInterface extends EntityFrontInterface
{
    public function getMetas(): Collection;
}
